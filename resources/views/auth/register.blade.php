<!DOCTYPE html>
<html lang="sw" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Officer Account | EcoHuru EPR Portal</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
        <div>
            <div class="mx-auto w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700 text-3xl shadow-sm">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h2 class="mt-4 text-center text-2xl font-black tracking-tight text-slate-900">
                Register Officer Account
            </h2>
            <p class="mt-1 text-center text-xs text-slate-500 font-medium">
                Create new NEMC or PRO Administrative Officer account
            </p>
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

        <form class="mt-6 space-y-4" action="{{ route('register') }}" method="POST">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                    <i class="bi bi-person text-slate-400"></i> Full Name
                </label>
                <input id="name" name="name" type="text" required value="{{ old('name') }}" placeholder="e.g. Juma Kassim" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600 text-slate-900">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                    <i class="bi bi-envelope text-slate-400"></i> Official Email Address
                </label>
                <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" placeholder="e.g. juma@nemc.go.tz" class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600 text-slate-900">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                    <i class="bi bi-key text-slate-400"></i> Password
                </label>
                <input id="password" name="password" type="password" required class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600 text-slate-900">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1 flex items-center gap-1">
                    <i class="bi bi-key-fill text-slate-400"></i> Confirm Password
                </label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-600 text-slate-900">
            </div>

            <div>
                <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs py-3 rounded-xl transition shadow flex items-center justify-center gap-2">
                    <i class="bi bi-check-circle-fill"></i> Complete Registration
                </button>
            </div>
        </form>

        <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100">
            Already registered?
            <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:text-emerald-800 ml-1">Sign In to Account</a>
        </div>
    </div>
</body>
</html>
