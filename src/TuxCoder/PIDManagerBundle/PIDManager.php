<?php

namespace TuxCoder\PIDManagerBundle;


class PIDManager
{
    /**
     * @var String
     */
		private $pid_path=null;
		
		
    public function __construct()
		{
		}

		/**
		 * @var string $pid_path
		 */
		public function setPidPath($pid_path){
		  $this->pid_path=$pid_path;
		}
		
		/**
		 * @return String Path to pid file
		 */
		public function getPidPath(){
			return $this->pid_path;
		}
		
		/**
		 * check if currently running
		 * 
		 * @return boolean
		 */
		public function isRunning(){
			if(file_exists($this->pid_path))
			{
				$pid=file_get_contents($this->pid_path);
				if($pid === null) {
				  //io error
				  throw new \TuxCoder\PIDManagerBundle\Exception\PIDFileIOErrorException('Can not read PID file: \"'.$this->pid_path.'"');
				}
				$pid=(int)$pid;
				if($pid>0 && file_exists( "/proc/$pid" ))
				{
				  //program running
					return true;
				}
			}
			
			return false;
		}
		
		/**
		 * Set current program to running and write the pid into the file
		 * 
		 * @return \TuxCoder\PIDManagerBundle\PIDManager $this
		 * @throws \TuxCoder\PIDManagerBundle\Exception\PIDFileIOErrorException
		 */
		public function setRunning(){
			if(@file_put_contents($this->pid_path,getmypid())===false) {
			  throw new \TuxCoder\PIDManagerBundle\Exception\PIDFileIOErrorException('Can not write PID file: "'.$this->pid_path.'"');
			}
			return $this;
		}
		
		/**
		 * Set current program to not running and delete the pid file
		 * 
		 * @return \TuxCoder\PIDManagerBundle\PIDManager $this
		 * @throws \TuxCoder\PIDManagerBundle\Exception\PIDFileIOErrorException
		 */
		public function setNotRunning(){
			if(file_exists($this->pid_path)){
				if(unlink($this->pid_path)===false) {
				  throw new \TuxCoder\PIDManagerBundle\Exception\PIDFileIOErrorException('Can not remove PID file: \"'.$this->pid_path.'"');
				}
			}
			return $this;
		}
}
