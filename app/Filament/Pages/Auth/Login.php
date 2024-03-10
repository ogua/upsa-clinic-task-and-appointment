<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Models\Contracts\FilamentUser;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class Login extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getEmailFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
            ])->statePath('data')
            ->model(User::class);
        }
        
        
        public function authenticate(): ?LoginResponse
        {
            try {
                $this->rateLimit(5);
            } catch (TooManyRequestsException $exception) {
                Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                    ]))
                    ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                        'seconds' => $exception->secondsUntilAvailable,
                        'minutes' => ceil($exception->secondsUntilAvailable / 60),
                        ]) : null)
                        ->danger()
                        ->send();
                        
                        return null;
                    }
                    
                    $data = $this->form->getState();
                    
                    if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
                        $this->throwFailureValidationException();
                    }
                    
                    $user = Filament::auth()->user();
                    
                    if (
                        ($user instanceof FilamentUser) &&
                        (! $user->canAccessPanel(Filament::getCurrentPanel()))
                        ) {
                            Filament::auth()->logout();
                            
                            $this->throwFailureValidationException();
                        }
                        
                        session()->regenerate();
                        
                        return app(LoginResponse::class);
                    }
                    
                    
                    protected function throwFailureValidationException(): never
                    {
                        throw ValidationException::withMessages([
                            'data.username' => __('filament-panels::pages/auth/login.messages.failed'),
                        ]);
                    }
                    
                    protected function getEmailFormComponent(): Component
                    {
                        return TextInput::make('username')
                        ->label(__('Email Address or ID'))
                        ->required()
                        ->autocomplete()
                        ->autofocus()
                        ->extraInputAttributes(['tabindex' => 1]);
                    }
                    
                    /**
                    * @param  array<string, mixed>  $data
                    * @return array<string, mixed>
                    */
                    protected function getCredentialsFromFormData(array $data): array
                    {
                        return [
                            'email' => $data['username'],
                            'password' => $data['password'],
                        ];
                    }
                }
                