<?php

namespace App;


use App\Entity\Project;
use App\Exception\DeployException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class Deployer
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }


    /**
     *
     * @param Project $project
     * @return string
     * @throws DeployException
     */
    public function deploy(Project $project)
    {
        $path = sys_get_temp_dir() . '/AwesomeDeployer';
        $this->filesystem->mkdir($path);

        chdir($path);
        $process = new Process('git clone ' . $project->getRepo() . ' project');
        $process->run();
        $pathProject = $path . '/project';
        chdir($pathProject);
        $out = $process->getOutput();

        switch ($project->getStrategy()) {
            case Project::STRATEGY_FABRIC:
                $command = $project->getCommand();
                break;
            default:
                throw new DeployException('Strategy not supported');
        }

        $deployment = new Process($command);
        $deployment->run();

        $this->filesystem->remove($path);

        $out .= $deployment->getOutput();

        return $out;
    }
}
