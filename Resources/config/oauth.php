<?php

use FOS\OAuthServerBundle\Command\CleanCommand;
use FOS\OAuthServerBundle\Command\CreateClientCommand;
use FOS\OAuthServerBundle\Controller\TokenController;
use FOS\OAuthServerBundle\Storage\OAuthStorage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('fos_oauth_server.server.class', \OAuth2\OAuth2::class);

    $services = $container->services();

    $services->set('fos_oauth_server.storage.default', OAuthStorage::class)
        ->public(false)
        ->args([
            new Reference('fos_oauth_server.client_manager'),
            new Reference('fos_oauth_server.access_token_manager'),
            new Reference('fos_oauth_server.refresh_token_manager'),
            new Reference('fos_oauth_server.auth_code_manager'),
            new Reference('fos_oauth_server.user_provider', ContainerConfigurator::IGNORE_ON_INVALID_REFERENCE),
            new Reference('security.password_hasher_factory'),
        ]);

    $services->set('fos_oauth_server.server', '%fos_oauth_server.server.class%')
        ->args([
            new Reference('fos_oauth_server.storage'),
            '%fos_oauth_server.server.options%',
        ]);

    $services->set(TokenController::class)
        ->args([new Reference('fos_oauth_server.server')]);

    $services->alias('fos_oauth_server.controller.token', TokenController::class)
        ->public(true);

    $services->set('fos_oauth_server.clean_command', CleanCommand::class)
        ->args([
            new Reference('fos_oauth_server.access_token_manager'),
            new Reference('fos_oauth_server.refresh_token_manager'),
            new Reference('fos_oauth_server.auth_code_manager'),
        ])
        ->tag('console.command');

    $services->set('fos_oauth_server.create_client_command', CreateClientCommand::class)
        ->args([new Reference('fos_oauth_server.client_manager')])
        ->tag('console.command');
};