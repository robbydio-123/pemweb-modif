<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function require_admin(): void
{
    if (($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: ../index.php');
        exit;
    }
}

function require_login(): void
{
    if (empty($_SESSION['role'])) {
        header('Location: ../index.php');
        exit;
    }
}

function is_customer(): bool
{
    return ($_SESSION['role'] ?? '') === 'customer';
}

function require_customer(): void
{
    if (!is_customer()) {
        header('Location: ../index.php');
        exit;
    }
}
