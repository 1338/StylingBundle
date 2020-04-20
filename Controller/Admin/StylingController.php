<?php

declare(strict_types=1);

namespace Sulu\Bundle\StylingBundle\Controller\Admin;

use Sulu\Bundle\StylingBundle\Admin\DoctrineListRepresentationFactory;
use Sulu\Bundle\StylingBundle\Entity\Styling;
use Sulu\Bundle\StylingBundle\Repository\StylingRepository;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sulu\Component\Rest\RestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StylingController extends RestController implements ClassResourceInterface
{
    /**
     * @var StylingRepository
     */
    private $repository;

    /**
     * @var DoctrineListRepresentationFactory
     */
    private $doctrineListRepresentationFactory;

    public function __construct(
        StylingRepository $repository,
        DoctrineListRepresentationFactory $doctrineListRepresentationFactory
    ) {
        $this->repository = $repository;
        $this->doctrineListRepresentationFactory = $doctrineListRepresentationFactory;
    }

    public function cgetAction(Request $request): Response
    {
        $locale = $request->query->get('locale');
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            Styling::RESOURCE_KEY,
            [],
            ['locale' => $locale]
        );

        return $this->handleView($this->view($listRepresentation));
    }

    public function getAction(int $id, Request $request): Response
    {
        $entity = $this->load($id, $request);
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        return $this->handleView($this->view($entity));
    }

    public function postAction(Request $request): Response
    {
        $entity = $this->create($request);

        $this->mapDataToEntity($request->request->all(), $entity);

        $this->save($entity);

        return $this->handleView($this->view($entity));
    }

    /**
     * @Rest\Post("/styling/{id}")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function postTriggerAction(int $id, Request $request): Response
    {
        $event = $this->repository->findById($id, $request->query->get('locale'));
        if (!$event) {
            throw new NotFoundHttpException();
        }

        switch ($request->query->get('action')) {
            case 'enable':
                $event->setEnabled(true);
                break;
            case 'disable':
                $event->setEnabled(false);
                break;
        }

        $this->repository->save($event);

        return $this->handleView($this->view($event));
    }

    public function putAction(int $id, Request $request): Response
    {
        $entity = $this->load($id, $request);
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        $this->mapDataToEntity($request->request->all(), $entity);

        $this->save($entity);

        return $this->handleView($this->view($entity));
    }

    public function deleteAction(int $id): Response
    {
        $this->remove($id);

        return $this->handleView($this->view());
    }

    /**
     * @param string[] $data
     * @param Styling $entity
     * @throws Exception
     */
    protected function mapDataToEntity(array $data, Styling $entity): void
    {
        $entity->setPrimaryColor($data['primaryColor']);
        $entity->setSecondaryColor($data['secondaryColor']);
    }

    protected function load(int $id, Request $request): ?Styling
    {
        return $this->repository->findById($id, $request->query->get('locale'));
    }

    protected function create(Request $request): Styling
    {
        return $this->repository->create($request->query->get('locale'));
    }

    protected function save(Styling $entity): void
    {
        $this->repository->save($entity);
    }

    protected function remove(int $id): void
    {
        $this->repository->remove($id);
    }
}
