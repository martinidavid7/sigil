<x-sigil.auth-layout title="Entrar">
    <div x-data="{ showPassword: false }">
        <h1 class="h3 font-weight-bold text-gray-900 mb-1">Bem-vindo(a)!</h1>
        <p class="text-muted mb-4">Acesse sua conta para continuar.</p>

        @session('status')
            <div class="alert alert-success small" role="status">{{ $value }}</div>
        @endsession

        @if ($errors->any())
            <div class="alert alert-danger small d-flex align-items-start" role="alert">
                <i class="fas fa-exclamation-circle mt-1 mr-2"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @if (config('sigil.demo.enabled'))
            <div class="border-left-info bg-light rounded p-3 mb-4 small">
                <div class="font-weight-bold text-info mb-1">
                    <i class="fas fa-flask mr-1"></i> Ambiente de demonstração
                </div>
                <div class="text-gray-700">
                    E-mail <code>{{ config('sigil.demo.email') }}</code> e senha <code>{{ config('sigil.demo.password') }}</code>.
                    Os dados podem ser alterados por outros visitantes.
                </div>
                <button type="button" class="btn btn-sm btn-outline-info mt-2"
                    x-on:click="$refs.email.value = @js(config('sigil.demo.email')); $refs.password.value = @js(config('sigil.demo.password')); $refs.submit.focus()">
                    <i class="fas fa-magic mr-1"></i> Preencher acesso de demonstração
                </button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="small font-weight-bold text-gray-700">E-mail</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" x-ref="email"
                        @class(['form-control', 'is-invalid' => $errors->has('email')])
                        placeholder="seu@email.com.br" required autofocus autocomplete="username">
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="small font-weight-bold text-gray-700">Senha</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input id="password" name="password" x-ref="password" x-bind:type="showPassword ? 'text' : 'password'"
                        type="password" @class(['form-control', 'is-invalid' => $errors->has('password')])
                        placeholder="Sua senha" required autocomplete="current-password">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-light border text-gray-500" x-on:click="showPassword = ! showPassword"
                            x-bind:title="showPassword ? 'Ocultar senha' : 'Mostrar senha'" aria-label="Mostrar ou ocultar senha">
                            <i class="fas" x-bind:class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="custom-control custom-checkbox small">
                    <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                    <label class="custom-control-label" for="remember_me">Lembrar de mim</label>
                </div>
                @if (Route::has('password.request'))
                    <a class="small" href="{{ route('password.request') }}">Esqueceu a senha?</a>
                @endif
            </div>

            <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold" x-ref="submit">
                <i class="fas fa-sign-in-alt mr-1"></i> Entrar
            </button>
        </form>

        @if (Route::has('register'))
            <hr class="my-4">
            <p class="text-center small text-muted mb-0">
                Ainda não tem acesso? <a href="{{ route('register') }}">Criar conta</a>
            </p>
        @endif
    </div>
</x-sigil.auth-layout>
