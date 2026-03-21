<?php

use Doctrine\ORM\EntityManager;
use FOS\OAuthServerBundle\Entity\AccessTokenManager;
use FOS\OAuthServerBundle\Entity\AuthCodeManager;
use FOS\OAuthServerBundle\Entity\ClientManager;
use FOS\OAuthServerBundle\Entity\RefreshTokenManager;
use FOS\OAuthServerBundle\Model\AccessTokenManagerInterface;
use FOS\OAuthServerBundle\Model\AuthCodeManagerInterface;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use FOS\OAuthServerBundle\Model\RefreshTokenManagerInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('fos_oauth_server.entity_manager', EntityManager::class)
        ->public(false)
        ->args(['%fos_oauth_server.model_manager_name%']);

    $services->set('fos_oauth_server.client_manager.default', ClientManager::class)
        ->args([new Reference('fos_oauth_server.entity_manager'), '%fos_oauth_server.model.client.class%']);

    $services->set('fos_oauth_server.access_token_manager.default', AccessTokenManager::class)
        ->args([new Reference('fos_oauth_server.entity_manager'), '%fos_oauth_server.model.access_token.class%']);

    $services->set('fos_oauth_server.refresh_token_manager.default', RefreshTokenManager::class)
        ->args([new Reference('fos_oauth_server.entity_manager'), '%fos_oauth_server.model.refresh_token.class%']);

    $services->set('fos_oauth_server.auth_code_manager.default', AuthCodeManager::class)
        ->args([new Reference('fos_oauth_server.entity_manager'), '%fos_oauth_server.model.auth_code.class%']);

    $services->alias(ClientManagerInterface::class, 'fos_oauth_server.client_manager.default');
    $services->alias(AccessTokenManagerInterface::class, 'fos_oauth_server.access_token_manager.default');
    $services->alias(RefreshTokenManagerInterface::class, 'fos_oauth_server.refresh_token_manager.default');
    $services->alias(AuthCodeManagerInterface::class, 'fos_oauth_server.auth_code_manager.default');
};