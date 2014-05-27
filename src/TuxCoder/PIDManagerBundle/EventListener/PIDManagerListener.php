<?php

namespace TuxCoder\PIDManagerBundle\EventListener;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Event\ConsoleExceptionEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

use TuxCoder\PIDManagerBundle\Exception\AllreadyRunningException;


/**
 * 
 * @author tuxcoder
 */
class PIDManagerListener {
  /**
   * @var \Symfony\Component\DependencyInjection\ContainerInterface
   */
  private $container;
  
  /**
   * 
   * @var Array
   */
  private $pid_paths;
  
  /**
   * @var \TuxCoder\PIDManagerBundle\PIDManager
   */
  private $pidManager;
  
  public function __construct(ContainerInterface $container) {
    $this->container=$container;
    $this->pidManager=$this->container->get('tuxcoder.pid_manager');
    
    if($container->hasParameter('pid_manager.pid_paths')) {
      $this->pid_paths=$container->getParameter('pid_manager.pid_paths');
    }else {
      $this->pid_paths=array();
    }
  }
  
  /**
   * will run on the kernel.event console.command
   * 
   * @param ConsoleCommandEvent $event
   * @throws AllreadyRunningException
   */
  public function onCommandStated(ConsoleCommandEvent $event) {
    
    //look for pid_path with the current command name
    if(isset($this->pid_paths[$event->getCommand()->getName()])) {
      $this->pidManager->setPidPath($this->pid_paths[$event->getCommand()->getName()]);
    //looking for default pid
    }else  if(isset($this->pid_paths['default'])) {
      $this->pidManager->setPidPath($this->pid_paths['default']);
    }else {
      return;
    }
    
    if($this->pidManager->isRunning()) {
      throw new AllreadyRunningException();
    }
    $this->pidManager->setRunning();
  }
  
  /**
   * will run on the kernel.event console.terminate
   * 
   * @param ConsoleTerminateEvent $event
   */
  public function onCommandTerminate(ConsoleTerminateEvent $event) {
    if(isset($this->pid_paths[$event->getCommand()->getName()])) {
      $this->pidManager->setNotRunning();
    }else  if(isset($this->pid_paths['default'])) {
      $this->pidManager->setNotRunning();
    }else {
      return;
    }
  }
  
  /**
   * will run on the kernel.event console.exception
   * 
   * @param ConsoleExceptionEvent $event
   */
  public function onCommandException(ConsoleExceptionEvent $event) {
    if(isset($this->pid_paths[$event->getCommand()->getName()])) {
      $this->pidManager->setNotRunning();
    }else  if(isset($this->pid_paths['default'])) {
      $this->pidManager->setNotRunning();
    }
  }
}