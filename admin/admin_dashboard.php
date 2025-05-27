<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
        import { getAuth, signInAnonymously, signInWithCustomToken } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-auth.js";
        import { getFirestore } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";

        // Global variables provided by the Canvas environment
        const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';
        const firebaseConfig = typeof __firebase_config !== 'undefined' ? JSON.parse(__firebase_config) : {};
        const initialAuthToken = typeof __initial_auth_token !== 'undefined' ? __initial_auth_token : null;

        let app, db, auth;

        document.addEventListener('DOMContentLoaded', async () => {
            try {
                // Initialize Firebase app
                app = initializeApp(firebaseConfig);
                db = getFirestore(app);
                auth = getAuth(app);

                // Sign in with custom token if available, otherwise anonymously
                if (initialAuthToken) {
                    await signInWithCustomToken(auth, initialAuthToken);
                    console.log("Signed in with custom token.");
                } else {
                    await signInAnonymously(auth);
                    console.log("Signed in anonymously.");
                }
            } catch (error) {
                console.error("Error initializing Firebase or signing in:", error);
                // Display a user-friendly message if Firebase fails to initialize
                const errorMessageDiv = document.createElement('div');
                errorMessageDiv.className = 'absolute top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-md';
                errorMessageDiv.innerHTML = `
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">${error.message}. Firebase initialization failed. Please try again later.</span>
                `;
                document.body.appendChild(errorMessageDiv);
            }
        });
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center p-4">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-4xl mt-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
            <a href="home.html" class="bg-red-500 text-white py-2 px-4 rounded-lg text-base font-semibold hover:bg-red-600 transition duration-300 shadow-md">
                Logout
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-6">
            <div class="bg-blue-100 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <h2 class="text-xl font-semibold text-blue-800 mb-3">Manage Karaoke Rooms</h2>
                <p class="text-gray-700 mb-4">Add, edit, or remove karaoke room types and their pricing.</p>
                <a href="manage_rooms.html" class="inline-block bg-blue-600 text-white py-2 px-4 rounded-lg text-md font-semibold hover:bg-blue-700 transition duration-300">
                    Go to Management
                </a>
            </div>

            <div class="bg-green-100 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <h2 class="text-xl font-semibold text-green-800 mb-3">Manage Users</h2>
                <p class="text-gray-700 mb-4">View and manage registered user accounts.</p>
                <a href="manage_users.html" class="inline-block bg-green-600 text-white py-2 px-4 rounded-lg text-md font-semibold hover:bg-green-700 transition duration-300">
                    Go to Management
                </a>
            </div>

            <div class="bg-purple-100 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <h2 class="text-xl font-semibold text-purple-800 mb-3">Manage Foods & Drinks</h2>
                <p class="text-gray-700 mb-4">Update the menu, prices, and availability of food and beverages.</p>
                <a href="manage_foods_drinks.html" class="inline-block bg-purple-600 text-white py-2 px-4 rounded-lg text-md font-semibold hover:bg-purple-700 transition duration-300">
                    Go to Management
                </a>
            </div>

            <div class="bg-yellow-100 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <h2 class="text-xl font-semibold text-yellow-800 mb-3">View Payments</h2>
                <p class="text-gray-700 mb-4">Access payment records and transaction history.</p>
                <a href="view_payments.html" class="inline-block bg-yellow-600 text-white py-2 px-4 rounded-lg text-md font-semibold hover:bg-yellow-700 transition duration-300">
                    Go to Payments
                </a>
            </div>
        </div>

        <div class="mt-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Recent Admin Activity</h2>
            <ul class="list-disc list-inside text-gray-700 space-y-2">
                <li>Updated Deluxe Room price.</li>
                <li>Added new user: Jane Smith.</li>
                <li>Processed payment for Reservation ID: RES-XYZ12345.</li>
            </ul>
        </div>
    </div>
</body>
</html>
