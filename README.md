SymfonyPIDManager
==========

Description
-----------
This bundle is intended to prevent running a command twice at the same time.

When you spool your mails for an example with an cronjob
and have a huge amount of mails,
it could otherwise happen that the old instance is still sending emails while
starting the command a second time.

###Function
The functionality is kept simple. Before executing a command this PIDManager writes
the current pid (process id) into a configured file(pid_path).
Before running a second time it will check this file and
return if the process is still running.

###Warning
This Bundle currently only works on Linux, because it detects running processes over the
procfs (/proc/$pid).

Usage
-----

###With Symfony2

AppKernel.php add the following lines

    new TuxCoder\PIDManagerBundle\PIDManagerBundle(),

config.yml add the following lines

    pid_manager:
      commands:
        - name: swiftmailer:spool:send
          pid_path: /run/user/symfony/mail_spool.pid
        - name: secoundCommandName
          pid_path: /path/to/otherPidFile.pid

###Without Symfony2
    $pidManager=new \TuxCoder\PIDManagerBundle\PIDManager();
    $pidManager->setPidPath('/path/to/pid/file');
    if(!$pidManager->isRunning()) {
      $pidManager->setRunning();

      //do some cool stuff

      $pidManager->setNotRunning();
    } else {
      //error program is running
    }


TODO
----

* Write some Tests
* Add an event handler for "AllreadyRunningException".
* If requested also make it runnable on Windows, Mac, BSD....
