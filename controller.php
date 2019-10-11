<?php

namespace Concrete\Package\TuricaneFunTourneySystem;

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Package\Package;
use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Database\EntityManager\Provider\ProviderAggregateInterface;
use Concrete\Core\Database\EntityManager\Provider\StandardPackageProvider;

class Controller extends Package implements ProviderAggregateInterface {

  protected $pkgHandle = 'turicane_fun_tourney_system';
  protected $appVersionRequired = '8.4';
  protected $pkgVersion = '0.120.27';
  protected $em;
  protected $pkgAutoloaderRegistries = array(
      'src/Tfts' => '\Tfts'
  );

  public function getPackageName() {
    return t('Turicane Fun Tourney System Package');
  }

  public function getPackageDescription() {
    return t('');
  }

  public function getEntityManagerProvider() {
    $provider = new StandardPackageProvider($this->app, $this, [
        'src/Entity' => 'Tfts\Entity'
    ]);
    return $provider;
  }

  public function on_start() {
    $this->registerRoutes();
    $al = AssetList::getInstance();
    $pkg = Package::getByHandle($this->pkgHandle);
    $al->register('javascript', 'tfts_notifications', 'js/tfts_notifications.js',
            array('minify' => false, 'combine' => false), $pkg
    );
      $al->register('javascript', 'tfts', 'js/tfts.js',
          array('minify' => false, 'combine' => false), $pkg
      );
  }

  public function install() {
    $pkg = parent::install();
    $ci = new ContentImporter();
    $ci->importContentFile($pkg->getPackagePath() . '/install.xml');
    $this->installDatabase();
  }

  public function upgrade() {
    parent::upgrade();
  }

  public function uninstall() {
    parent::uninstall();
  }

  public function registerRoutes() {
    $router = $this->app->make('router');
    // This Route is needed to "capture" the Data sent by the Trackmania result logger
    $router->post('/tfts/api/trackmania', 'Tfts\Tfts::processTrackmaniaData');
    // Register other routes for interface actions and pass them along to the tfts
    $router->post('/tfts/api/joinUserPool', 'Tfts\Tfts::joinUserPool');
  }

}
