<?php

namespace App\Test\Controller;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EvenementControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EvenementRepository $repository;
    private string $path = '/evenement/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Evenement::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Evenement index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'evenement[nomevent]' => 'Testing',
            'evenement[adresseevent]' => 'Testing',
            'evenement[capaciteevent]' => 'Testing',
            'evenement[nbrticketdispo]' => 'Testing',
            'evenement[datedebutevent]' => 'Testing',
            'evenement[datefinevent]' => 'Testing',
            'evenement[descriptionevent]' => 'Testing',
            'evenement[prixentre]' => 'Testing',
            'evenement[image1]' => 'Testing',
        ]);

        self::assertResponseRedirects('/evenement/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Evenement();
        $fixture->setNomevent('My Title');
        $fixture->setAdresseevent('My Title');
        $fixture->setCapaciteevent('My Title');
        $fixture->setNbrticketdispo('My Title');
        $fixture->setDatedebutevent('My Title');
        $fixture->setDatefinevent('My Title');
        $fixture->setDescriptionevent('My Title');
        $fixture->setPrixentre('My Title');
        $fixture->setImage1('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Evenement');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Evenement();
        $fixture->setNomevent('My Title');
        $fixture->setAdresseevent('My Title');
        $fixture->setCapaciteevent('My Title');
        $fixture->setNbrticketdispo('My Title');
        $fixture->setDatedebutevent('My Title');
        $fixture->setDatefinevent('My Title');
        $fixture->setDescriptionevent('My Title');
        $fixture->setPrixentre('My Title');
        $fixture->setImage1('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'evenement[nomevent]' => 'Something New',
            'evenement[adresseevent]' => 'Something New',
            'evenement[capaciteevent]' => 'Something New',
            'evenement[nbrticketdispo]' => 'Something New',
            'evenement[datedebutevent]' => 'Something New',
            'evenement[datefinevent]' => 'Something New',
            'evenement[descriptionevent]' => 'Something New',
            'evenement[prixentre]' => 'Something New',
            'evenement[image1]' => 'Something New',
        ]);

        self::assertResponseRedirects('/evenement/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomevent());
        self::assertSame('Something New', $fixture[0]->getAdresseevent());
        self::assertSame('Something New', $fixture[0]->getCapaciteevent());
        self::assertSame('Something New', $fixture[0]->getNbrticketdispo());
        self::assertSame('Something New', $fixture[0]->getDatedebutevent());
        self::assertSame('Something New', $fixture[0]->getDatefinevent());
        self::assertSame('Something New', $fixture[0]->getDescriptionevent());
        self::assertSame('Something New', $fixture[0]->getPrixentre());
        self::assertSame('Something New', $fixture[0]->getImage1());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Evenement();
        $fixture->setNomevent('My Title');
        $fixture->setAdresseevent('My Title');
        $fixture->setCapaciteevent('My Title');
        $fixture->setNbrticketdispo('My Title');
        $fixture->setDatedebutevent('My Title');
        $fixture->setDatefinevent('My Title');
        $fixture->setDescriptionevent('My Title');
        $fixture->setPrixentre('My Title');
        $fixture->setImage1('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/evenement/');
    }
}
