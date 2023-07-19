<?php

namespace App\Test\Controller;

use App\Entity\Calendar;
use App\Repository\CalendarRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CalendarControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private CalendarRepository $repository;
    private string $path = '/calendar/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Calendar::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Calendar index');

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
            'calendar[title]' => 'Testing',
            'calendar[start]' => 'Testing',
            'calendar[end]' => 'Testing',
            'calendar[description]' => 'Testing',
            'calendar[all_day]' => 'Testing',
            'calendar[background_color]' => 'Testing',
            'calendar[border_color]' => 'Testing',
            'calendar[text_color]' => 'Testing',
        ]);

        self::assertResponseRedirects('/calendar/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Calendar();
        $fixture->setTitle('My Title');
        $fixture->setStart('My Title');
        $fixture->setEnd('My Title');
        $fixture->setDescription('My Title');
        $fixture->setAll_day('My Title');
        $fixture->setBackground_color('My Title');
        $fixture->setBorder_color('My Title');
        $fixture->setText_color('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Calendar');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Calendar();
        $fixture->setTitle('My Title');
        $fixture->setStart('My Title');
        $fixture->setEnd('My Title');
        $fixture->setDescription('My Title');
        $fixture->setAll_day('My Title');
        $fixture->setBackground_color('My Title');
        $fixture->setBorder_color('My Title');
        $fixture->setText_color('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'calendar[title]' => 'Something New',
            'calendar[start]' => 'Something New',
            'calendar[end]' => 'Something New',
            'calendar[description]' => 'Something New',
            'calendar[all_day]' => 'Something New',
            'calendar[background_color]' => 'Something New',
            'calendar[border_color]' => 'Something New',
            'calendar[text_color]' => 'Something New',
        ]);

        self::assertResponseRedirects('/calendar/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getStart());
        self::assertSame('Something New', $fixture[0]->getEnd());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getAll_day());
        self::assertSame('Something New', $fixture[0]->getBackground_color());
        self::assertSame('Something New', $fixture[0]->getBorder_color());
        self::assertSame('Something New', $fixture[0]->getText_color());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Calendar();
        $fixture->setTitle('My Title');
        $fixture->setStart('My Title');
        $fixture->setEnd('My Title');
        $fixture->setDescription('My Title');
        $fixture->setAll_day('My Title');
        $fixture->setBackground_color('My Title');
        $fixture->setBorder_color('My Title');
        $fixture->setText_color('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/calendar/');
    }
}
