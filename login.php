<?php
session_start();
require 'koneksi.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/index.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_username = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check email or username
    $query = "SELECT * FROM users WHERE email='$email_or_username' OR username='$email_or_username'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['username'] = $row['username'];
            
            // Redirect based on role
            if ($row['role'] == 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Akun tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login - GMusic</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📻</text></svg>">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#1db954",
                        "background-light": "#f6f8f7",
                        "background-dark": "#121212",
                        "card-dark": "#181818",
                    },
                    fontFamily: {
                        "display": ["Public Sans"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display min-h-screen flex flex-col">
    <div class="relative flex min-h-screen w-full flex-col items-center justify-center p-4">
        <header class="mb-10 flex items-center gap-2">
            <div class="text-primary">
                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 6H42L36 24L42 42H6L12 24L6 6Z"></path>
                </svg>
            </div>
            <h1 class="text-slate-900 dark:text-slate-100 text-3xl font-bold tracking-tight">GMusic</h1>
        </header>

        <main class="w-full max-w-[480px] bg-white dark:bg-card-dark rounded-xl shadow-2xl p-8 md:p-12">
            <h2 class="text-slate-900 dark:text-slate-100 text-3xl font-bold text-center mb-10">Log in to GMusic</h2>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-500 text-white p-3 rounded-md mb-6 text-sm text-center font-medium">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-slate-900 dark:text-slate-100 text-sm font-bold tracking-wide">
                        Email or username
                    </label>
                    <input name="email" required class="w-full bg-slate-100 dark:bg-[#2a2a2a] border-2 border-transparent focus:border-primary focus:ring-0 rounded-lg text-slate-900 dark:text-slate-100 h-14 px-4 text-base transition-colors placeholder:text-slate-500 dark:placeholder:text-slate-400" placeholder="Email or username" type="text"/>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-slate-900 dark:text-slate-100 text-sm font-bold tracking-wide">
                        Password
                    </label>
                    <div class="relative flex items-center">
                        <input name="password" required id="password-input" class="w-full bg-slate-100 dark:bg-[#2a2a2a] border-2 border-transparent focus:border-primary focus:ring-0 rounded-lg text-slate-900 dark:text-slate-100 h-14 px-4 text-base transition-colors placeholder:text-slate-500 dark:placeholder:text-slate-400" placeholder="Password" type="password"/>
                        <button class="absolute right-4 text-slate-500 hover:text-primary transition-colors" type="button" onclick="togglePassword()">
                            <span class="material-symbols-outlined" id="toggle-icon">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center space-x-2 py-2">
                    <input class="w-4 h-4 text-primary bg-slate-200 dark:bg-[#2a2a2a] border-none rounded focus:ring-primary focus:ring-offset-0" id="remember" type="checkbox"/>
                    <label class="text-sm text-slate-700 dark:text-slate-300" for="remember">Remember me</label>
                </div>

                <button class="w-full bg-primary hover:bg-opacity-90 text-black text-base font-bold py-4 rounded-full transition-all uppercase tracking-widest shadow-lg" type="submit">
                    Log In
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-slate-200 dark:border-white/10 text-center">
                <a class="text-sm text-slate-600 dark:text-slate-400 hover:text-primary hover:underline transition-colors block mb-4" href="#">
                    Forgot your password?
                </a>
                <p class="text-slate-700 dark:text-slate-300">
                    Don't have an account? 
                    <a class="text-primary font-bold hover:underline ml-1" href="register.php">Sign up for GMusic</a>
                </p>
            </div>
        </main>

        <footer class="mt-12 text-center text-slate-500 text-xs">
            <p>© 2026 GMusic. All rights reserved.</p>
        </footer>
    </div>

    <script>
        function togglePassword() {
            const temp = document.getElementById("password-input");
            const icon = document.getElementById("toggle-icon");
            if (temp.type === "password") {
                temp.type = "text";
                icon.innerText = "visibility_off";
            }
            else {
                temp.type = "password";
                icon.innerText = "visibility";
            }
        }
    </script>
</body>
</html>
