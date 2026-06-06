<?php
session_start();
require 'koneksi.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if ($password !== $confirm_password) {
        $error = "Password tidak cocok!";
    } else {
        // Check if email already exists
        $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' OR username='$username'");
        if (mysqli_num_rows($check_email) > 0) {
            $error = "Email atau username sudah terdaftar!";
        } else {
            // Hash password and insert
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            
            if (mysqli_query($conn, $query)) {
                $success = "Pendaftaran berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Sign Up - GMusic</title>
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
        <header class="mb-6 flex items-center gap-2">
            <div class="text-primary">
                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 6H42L36 24L42 42H6L12 24L6 6Z"></path>
                </svg>
            </div>
            <h1 class="text-slate-900 dark:text-slate-100 text-3xl font-bold tracking-tight">GMusic</h1>
        </header>

        <main class="w-full max-w-[480px] bg-white dark:bg-card-dark rounded-xl shadow-2xl p-8 md:p-10">
            <h2 class="text-slate-900 dark:text-slate-100 text-2xl font-bold text-center mb-8">Sign up for free to start listening.</h2>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-500 text-white p-3 rounded-md mb-6 text-sm text-center font-medium">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="bg-green-500 text-white p-3 rounded-md mb-6 text-sm text-center font-medium">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="space-y-4">
                <div class="space-y-2">
                    <label class="block text-slate-900 dark:text-slate-100 text-sm font-bold tracking-wide">
                        What's your email?
                    </label>
                    <input name="email" required type="email" class="w-full bg-slate-100 dark:bg-[#2a2a2a] border-2 border-transparent focus:border-primary focus:ring-0 rounded-lg text-slate-900 dark:text-slate-100 h-12 px-4 text-base transition-colors placeholder:text-slate-500 dark:placeholder:text-slate-400" placeholder="Enter your email."/>
                </div>

                <div class="space-y-2">
                    <label class="block text-slate-900 dark:text-slate-100 text-sm font-bold tracking-wide">
                        Create a password
                    </label>
                    <div class="relative flex items-center">
                        <input name="password" required id="password-input" class="w-full bg-slate-100 dark:bg-[#2a2a2a] border-2 border-transparent focus:border-primary focus:ring-0 rounded-lg text-slate-900 dark:text-slate-100 h-12 px-4 text-base transition-colors placeholder:text-slate-500 dark:placeholder:text-slate-400" placeholder="Create a password." type="password"/>
                        <button class="absolute right-4 text-slate-500 hover:text-primary transition-colors" type="button" onclick="togglePassword('password-input', 'toggle-icon-1')">
                            <span class="material-symbols-outlined" id="toggle-icon-1">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-slate-900 dark:text-slate-100 text-sm font-bold tracking-wide">
                        Confirm password
                    </label>
                    <div class="relative flex items-center">
                        <input name="confirm_password" required id="confirm-password-input" class="w-full bg-slate-100 dark:bg-[#2a2a2a] border-2 border-transparent focus:border-primary focus:ring-0 rounded-lg text-slate-900 dark:text-slate-100 h-12 px-4 text-base transition-colors placeholder:text-slate-500 dark:placeholder:text-slate-400" placeholder="Confirm your password." type="password"/>
                        <button class="absolute right-4 text-slate-500 hover:text-primary transition-colors" type="button" onclick="togglePassword('confirm-password-input', 'toggle-icon-2')">
                            <span class="material-symbols-outlined" id="toggle-icon-2">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-slate-900 dark:text-slate-100 text-sm font-bold tracking-wide">
                        What should we call you?
                    </label>
                    <input name="username" required type="text" class="w-full bg-slate-100 dark:bg-[#2a2a2a] border-2 border-transparent focus:border-primary focus:ring-0 rounded-lg text-slate-900 dark:text-slate-100 h-12 px-4 text-base transition-colors placeholder:text-slate-500 dark:placeholder:text-slate-400" placeholder="Enter a profile name."/>
                    <p class="text-xs text-slate-500 dark:text-slate-400">This appears on your profile.</p>
                </div>

                <div class="pt-4">
                    <button class="w-full bg-primary hover:bg-opacity-90 text-black text-base font-bold py-4 rounded-full transition-all uppercase tracking-widest shadow-lg" type="submit">
                        Sign Up
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-8 border-t border-slate-200 dark:border-white/10 text-center">
                <p class="text-slate-700 dark:text-slate-300">
                    Have an account? 
                    <a class="text-primary font-bold hover:underline ml-1" href="login.php">Log in</a>
                </p>
            </div>
        </main>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const temp = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
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
