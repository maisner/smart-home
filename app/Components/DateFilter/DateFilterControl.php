<?php declare(strict_types = 1);

namespace Maisner\SmartHome\Components\DateFilter;


use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\ArrayHash;

/**
 * Class DateFilterControl
 * @package Maisner\SmartHome\Components\DateFilter
 * @method onFilter(DateFilterControl $control, \DateTimeImmutable $from, \DateTimeImmutable $to)
 * @method onValidateErrors(DateFilterControl $control, array $errors)
 */
class DateFilterControl extends Control {

	/** @var array|callable[]|\Closure[] */
	public $onFilter = [];

	/** @var array|callable[]|\Closure[] */
	public $onValidateErrors = [];

	public const DATE_FORMAT = 'Y-m-d';

	/** @var \DateTimeImmutable */
	private $defaultFrom;

	/** @var \DateTimeImmutable */
	private $defaultTo;

	/**
	 * DateFilterControl constructor.
	 * @param \DateTimeImmutable $defaultFrom
	 * @param \DateTimeImmutable $defaultTo
	 */
	public function __construct(\DateTimeImmutable $defaultFrom, \DateTimeImmutable $defaultTo) {
		parent::__construct();

		$this->defaultFrom = $defaultFrom;
		$this->defaultTo = $defaultTo;
	}

	public function render(): void {
		/** @var Template $template */
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/default.latte');

		$template->render();
	}

	/**
	 * @return Form
	 * @throws \Exception
	 */
	protected function createComponentFilterDate(): Form {
		$form = new Form();
		$form->addText('from', 'Od')
			->setDefaultValue($this->defaultFrom->format(self::DATE_FORMAT))
			->setType('date')
			->setRequired();

		$form->addText('to', 'Do')
			->setDefaultValue($this->defaultTo->format(self::DATE_FORMAT))
			->setType('date')
			->setRequired();

		$form->addSubmit('submit', 'Odeslat');

		$form->onValidate[] = function (Form $form, ArrayHash $values): void {
			$from = new \DateTimeImmutable($values->from);
			$to = new \DateTimeImmutable($values->to);

			if ($to < $from) {
				$form->addError('Date `to` must be greater than `from`');
			}

			if ($form->hasErrors()) {
				$this->onValidateErrors($this, $form->getErrors());
			}
		};

		$form->onSuccess[] = function (Form $form, ArrayHash $values): void {
			$from = new \DateTimeImmutable($values->from);
			$to = new \DateTimeImmutable($values->to);

			$this->onFilter($this, $from, $to);
		};

		return $form;
	}

}