<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Acceso Denegado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 md:p-12 rounded-3xl shadow-xl max-w-lg w-full text-center border border-slate-100">
        
        <div class="flex justify-center mb-6">
            <div class="bg-red-50 p-5 rounded-full">
                <svg class="h-16 w-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
        </div>
        
        <h1 class="text-3xl font-extrabold text-slate-900 mb-3">Acceso Restringido</h1>
        
        <p class="text-slate-500 mb-8 leading-relaxed">
            Lo sentimos, no cuentas con los permisos necesarios para visualizar esta sección. Si consideras que esto es un error, por favor contacta al administrador del sistema.
        </p>
        
        @php
            $redirectUrl = url('/'); // Ruta por defecto si no está logueado o no tiene rol específico
            
            if (auth()->check()) {
                $user = auth()->user();
                if ($user->hasRole('administrador')) {
                    $redirectUrl = url('/dashboard');
                } elseif ($user->hasRole('ventas')) {
                    $redirectUrl = url('/sales');
                } elseif ($user->hasRole('caseta')) {
                    $redirectUrl = url('/scanner');
                }
            }
        @endphp
        
        <a href="{{ $redirectUrl }}" class="inline-flex items-center justify-center w-full sm:w-auto bg-slate-900 text-white px-8 py-3 rounded-xl font-medium hover:bg-slate-800 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a mi inicio
        </a>
        
    </div>
</body>
</html>