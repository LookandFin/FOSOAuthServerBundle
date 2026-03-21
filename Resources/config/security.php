<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->parameters()
        ->set('fos_oauth_server.security.authentication.authenticator.class', \FOS\OAuthServerBundle\Security\Authentication\Authenticator\OAuthAuthenticator::class)
        ->set('fos_oauth_server.security.entry_point.class', \FOS\OAuthServerBundle\Security\EntryPoint\OAuthEntryPoint::class);

    $services = $container->services();

    $services->set('fos_oauth_server.security.authentication.authenticator', '%fos_oauth_server.security.authentication.authenticator.class%')
        ->public(false)
        ->args([
            new Reference('fos_oauth_server.server'),
            new Reference('security.user_checker'),
            null, // user provider, injecté dynamiquement
        ]);

    $services->set('fos_oauth_server.security.entry_point', '%fos_oauth_server.security.entry_point.class%')
        ->public(false)
        ->args([new Reference('fos_oauth_server.server')]);
};