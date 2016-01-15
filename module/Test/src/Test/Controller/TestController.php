<?php

namespace Test\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Test\Entity\Test;
use Test\Form\TestForm;
use Doctrine\ORM\EntityManager;

class TestController extends AbstractActionController
{
    protected $em;

    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    public function indexAction()
    {
        return new ViewModel(array(
            'albums' => $this->getEntityManager()->getRepository('Test\Entity\Test')->findAll(),
        ));
    }

    public function addAction()
    {
        $form = new TestForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $test = new Test();
            $form->setInputFilter($test->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $test->exchangeArray($form->getData());
                $this->getEntityManager()->persist($test);
                $this->getEntityManager()->flush();

                return $this->redirect()->toRoute('test');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('test', array(
                'action' => 'add'
            ));
        }

        $test = $this->getEntityManager()->find('Test\Entity\Test', $id);
        if (!$test) {
            return $this->redirect()->toRoute('test', array(
                'action' => 'index'
            ));
        }

        $form  = new TestForm();
        $form->bind($test);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($test->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getEntityManager()->flush();

                // Redirect to list of Test
                return $this->redirect()->toRoute('test');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('test');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $test = $this->getEntityManager()->find('Test\Entity\Test', $id);
                if ($test) {
                    $this->getEntityManager()->remove($test);
                    $this->getEntityManager()->flush();
                }
            }

            // Redirect to list of Test
            return $this->redirect()->toRoute('test');
        }

        return array(
            'id'    => $id,
            'album' => $this->getEntityManager()->find('Test\Entity\Test', $id)
        );
    }
}
