<?php
namespace Zurv;

interface Bootstrapper {
  /**
   * @param \Zurv\Application $application
   */
  public function __construct(Application $application);

  /**
   * Shows, wether a section has been bootstrapped successful or not.
   *
   * @see \Zurv\Bootstrapper\Base for a sample implementation.
   */
  public function bootstrap($methodOrSection);
}