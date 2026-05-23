<?php
namespace App\Core;

class Config {
    const DB_HOST = '127.0.0.1'; 
    const DB_PORT = '3307';
    const DB_NAME = 'ecommerce';
    const DB_USER = 'master';
    const DB_PASS = 'chaudeptraivcl';
    const DB_CHARSET = 'utf8mb4';

    const APP_NAME = 'TechStore Luxury';
    const DEFAULT_CONTROLLER = 'HomeController';
    const DEFAULT_ACTION = 'index';
    public static function getBaseUrl(): string {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST']; // Lấy localhost:port hoặc domain.com
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $publicPath = rtrim($scriptName, '/public');
        $baseUrl = $protocol . "://" . $host . $publicPath;

        return rtrim($baseUrl, '/');
    }

    const ENV = 'dev'; 
}

if (!defined('BASE_URL')) {
    define('BASE_URL', Config::getBaseUrl());
}