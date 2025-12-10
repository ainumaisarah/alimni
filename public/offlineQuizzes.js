// Offline Quizzes IndexedDB Helper
const DB_NAME = 'alimni-offline-db';
const DB_VERSION = 1;
let db;

// Open or create IndexedDB
function openDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, DB_VERSION);

        request.onupgradeneeded = (event) => {
            db = event.target.result;
            if (!db.objectStoreNames.contains('quizzes')) {
                db.createObjectStore('quizzes', { keyPath: 'id' }); // store quiz data
            }
            if (!db.objectStoreNames.contains('submissions')) {
                db.createObjectStore('submissions', { keyPath: 'id', autoIncrement: true }); // store offline answers
            }
        };

        request.onsuccess = (event) => {
            db = event.target.result;
            resolve(db);
        };

        request.onerror = (event) => {
            reject('DB error: ' + event.target.errorCode);
        };
    });
}

// Save quiz for offline viewing
async function saveQuizOffline(quiz) {
    await openDB();
    const tx = db.transaction('quizzes', 'readwrite');
    const store = tx.objectStore('quizzes');
    store.put(quiz);
    return tx.complete;
}

// Get quiz from IndexedDB
async function getQuizOffline(id) {
    await openDB();
    return new Promise((resolve, reject) => {
        const tx = db.transaction('quizzes', 'readonly');
        const store = tx.objectStore('quizzes');
        const request = store.get(id);

        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(null);
    });
}

// Save offline submission
async function saveSubmissionOffline(submission) {
    await openDB();
    const tx = db.transaction('submissions', 'readwrite');
    const store = tx.objectStore('submissions');
    store.put(submission);
    return tx.complete;
}

// Get all pending submissions
async function getPendingSubmissions() {
    await openDB();
    return new Promise((resolve, reject) => {
        const tx = db.transaction('submissions', 'readonly');
        const store = tx.objectStore('submissions');
        const request = store.getAll();

        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject([]);
    });
}

// Delete submission after syncing
async function deleteSubmission(id) {
    await openDB();
    const tx = db.transaction('submissions', 'readwrite');
    const store = tx.objectStore('submissions');
    store.delete(id);
    return tx.complete;
}

// Listen for messages from the service worker (for background sync)
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.addEventListener('message', async (event) => {
        if (event.data.action === 'sync-submissions') {
            const submissions = await getPendingSubmissions();

            for (const sub of submissions) {
                try {
                    const response = await fetch(`/student/quizzes/${sub.quiz_id}/submit`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(sub.answers)
                    });

                    if (response.ok) {
                        await deleteSubmission(sub.id); // remove from IndexedDB
                        console.log('Offline submission synced:', sub.quiz_id);
                    }
                } catch (err) {
                    console.log('Sync failed for quiz', sub.quiz_id);
                }
            }
        }
    });
}
