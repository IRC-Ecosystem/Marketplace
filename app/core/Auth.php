<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function has_role(array|string $roles): bool
{
    $user = current_user();
    $roles = is_array($roles) ? $roles : [$roles];
    return $user && in_array($user['role'], $roles, true);
}

function role_home(?array $user = null): string
{
    $user = $user ?? current_user();

    return match ($user['role'] ?? null) {
        'admin' => 'admin',
        'seller' => 'toko',
        default => 'user',
    };
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: ' . BASEURL . 'auth/login');
        exit;
    }
}

function require_role(array|string $roles): void
{
    require_login();
    if (!has_role($roles)) {
        header('Location: ' . BASEURL . role_home());
        exit;
    }
}

function flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return null;
    }

    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}
