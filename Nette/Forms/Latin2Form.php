<?php

    namespace Nette\Forms;
    use Nette, Nette\Forms;

    class Latin2Form extends Form
    {

        public function __construct($name = NULL, $parent = NULL)
        {
            parent::__construct();
            // parent::setEncoding('ISO-8859-2');
            parent::setTranslator(new IconvTranslator('ISO-8859-2', 'UTF-8'));
        }

        public function setTranslator(ITranslator $translator = NULL)
        {
            $this->getTranslator()->setNext($translator);
        }

        public function getValues()
        {
            $values = parent::getValues();
            $translator = new IconvTranslator('UTF-8', 'ISO-8859-2');
            foreach ($values as & $value) {
                $value = $translator->translate($value);
            }
            return $values;
        }
    }

    class IconvTranslator extends Nette\Object implements Nette\Localization\ITranslator
    {
        private $fromEncoding;
        private $toEncoding;

        private $next;

        public function __construct($fromEncoding, $toEncoding)
        {
            $this->fromEncoding = $fromEncoding;
            $this->toEncoding = $toEncoding .'//TRANSLIT';
        }

        public function setNext(ITranslator $next)
        {
            $this->next = $next;
        }

        public function translate($s, $count = NULL)
        {
            $s = iconv($this->fromEncoding, $this->toEncoding, $s);
            if ($this->next) {
                $s = $this->next->translate($s, $count);
            }
            return $s;
        }
    }