<x-guest-layout title="Registro de Usuario">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Nombre </label>
            <!-- Mantiene autofocus -->
            <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="given-name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Apellido-->
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellidos</label>
            <!-- Se quitó autofocus / corregido autocomplete -->
            <input id="apellido" class="form-control @error('apellido') is-invalid @enderror" type="text" name="apellido" value="{{ old('apellido') }}" required autocomplete="family-name">
            @error('apellido')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- ci-->
        <div class="mb-3">
            <label for="ci" class="form-label">Carnet de Identidad</label>
            <!-- Se quitó autofocus y autocomplete no estándar -->
            <input id="ci" class="form-control @error('ci') is-invalid @enderror" type="text" name="ci" value="{{ old('ci') }}" required>
            @error('ci')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Teléfono-->
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <!-- Se quitó autofocus / corregido autocomplete -->
            <input id="telefono" class="form-control @error('telefono') is-invalid @enderror" type="text" name="telefono" value="{{ old('telefono') }}" required autocomplete="tel">
            @error('telefono')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2 mb-3">
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </div>

        <div class="text-center">
            <a class="text-decoration-none small text-muted" href="{{ route('login') }}">
                ¿Ya estás registrado? Inicia sesión
            </a>
        </div>
    </form>
</x-guest-layout>
