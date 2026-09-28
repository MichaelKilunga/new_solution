<!DOCTYPE html>
<html lang="sw" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | EcoHuru EPR Portal</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="mx-auto w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700 text-3xl shadow-sm">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h2 class="mt-4 text-center text-2xl font-black tracking-tight text-slate-900">
                Sign In to EcoHuru Admin Portal
            </h2>
            <p class="mt-1 text-center text-xs text-slate-500 font-medium">
                Authorized Regulatory Officers & PRO System Administrators
            </p>
        </div>

        <!-- System Credentials Banner -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3.5 text-xs text-blue-950 space-y-1">
            <div class="font-bold text-blue-900 flex items-center gap-1.5">
                <i class="bi bi-info-circle-fill text-blue-600"></i> Admin Access Credentials:
            </div>
            <div class="font-mono text-[11px] text-blue-800">
                Email: <strong>admin@nemc.go.tz</strong><br>
                Password: <strong>password</strong>
            </div>
        </div>

        @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-xl p-3.5 text-xs text-rose-800">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="mt-6 space-y-5" action="{{ route('login') }}" method="POST">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                    <i class="bi bi-envelope text-slate-400"></i> Email Address
                </label>
                <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email', 'admin@nemc.go.tz') }}" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600 text-slate-900">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                    <i class="bi bi-key text-slate-400"></i> Password
                </label>
                <input id="password" name="password" type="password" autocomplete="current-password" required value="password" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600 text-slate-900">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <span class="text-slate-600 font-medium">Remember me</span>
                </label>

                <a href="{{ route('public.portal') }}" class="font-bold text-emerald-700 hover:text-emerald-800">
                    &larr; Back to Public Portal
                </a>
            </div>

            <div>
                <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs py-3 rounded-xl transition shadow flex items-center justify-center gap-2">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In to Dashboard
                </button>
            </div>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100">
            Don't have an officer account?
            <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:text-emerald-800 ml-1">Register New Officer</a>
        </div>
    </div>
</body>
</html>
