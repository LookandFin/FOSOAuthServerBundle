<?php

use FOS\OAuthServerBundle\Controller\AuthorizeController;
use FOS\OAuthServerBundle\Form\Handler\AuthorizeFormHandler;
use FOS\OAuthServerBundle\Form\Type\AuthorizeFormType;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Form\Form;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set('fos_oauth_server.authorize.form', Form::class)
        ->args([
            '%fos_oauth_server.authorize.form.name%',
            '%fos_oauth_server.authorize.form.type%',
            null,
            ['validation_groups' => '%fos_oauth_server.authorize.form.validation_groups%'],
        ]);

    $services->set('fos_oauth_server.authorize.form.type', AuthorizeFormType::class)
        ->tag('form.type', ['alias' => 'fos_oauth_server_authorize']);

    $services->set('fos_oauth_server.authorize.form.handler.default', AuthorizeFormHandler::class)
        ->args([
            new Reference('fos_oauth_server.authorize.form'),
            new Reference('request_stack'),
        ]);

    $services->set('fos_oauth_server.controller.authorize', AuthorizeController::class)
        ->public(true)
        ->args([
            new Reference('request_stack'),
            new Reference('fos_oauth_server.authorize.form'),
            new Reference('fos_oauth_server.authorize.form.handler'),
            new Reference('fos_oauth_server.server'),
            new Reference('security.token_storage'),
            new Reference('router'),
            new Reference('fos_oauth_server.client_manager'),
            new Reference('event_dispatcher'),
            new Reference('twig'),
            new Reference('session', ContainerConfigurator::IGNORE_ON_INVALID_REFERENCE),
        ]);
};