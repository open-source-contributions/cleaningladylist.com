<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Checkbox;
use App\Entity\Checklist;
use App\Entity\Project;
use App\Form\ProjectFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

final class ProjectController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private RouterInterface $router;

    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * @Route("/", name="project.new")
     */
    public function create(Request $request): Response
    {
        $project = new Project();
        $projectForm = $this->createForm(ProjectFormType::class, $project);
        $projectForm->handleRequest($request);
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            return $this->processFormRequest($project);
        }

        return $this->render('project/create.html.twig', [
            'projectForm' => $projectForm->createView(),
        ]);
    }

    /**
     * @Route("/project/{id}", name="project.show")
     */
    public function show(Project $project): Response
    {
        $currentFramework = $project->getCurrentFramework();
        $checkboxes = $this->entityManager->getRepository(Checkbox::class)->findByFramework($currentFramework);

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'checkboxes' => $checkboxes,
        ]);
    }

    private function processFormRequest(Project $project): RedirectResponse
    {
        /** @var Checklist $checklist */
        $checklist = new Checklist();
        $checklist->setProject($project);
        $project->addChecklist($checklist);

        $this->entityManager->persist($checklist);
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return new RedirectResponse($this->router->generate('project.show', ['id' => $project->getId()]));
    }
}
