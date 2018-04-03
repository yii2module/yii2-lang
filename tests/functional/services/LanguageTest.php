<?php

namespace tests\functional\services;

use Codeception\Test\Unit;
use Yii;
use yii2lab\domain\data\Query;
use yii2module\lang\domain\enums\LanguageEnum;

class LanguageTest extends Unit
{
	
	public function testCurrent()
	{
		expect(LanguageEnum::RU)->equals(Yii::$app->language);
		
		$entity = Yii::$domain->lang->language->oneCurrent();
		$this->tester->assertEntity([
			'code' => 'ru',
			'locale' => LanguageEnum::RU,
			'is_main' => true,
		], $entity);
	}
	
	public function testSwitchLang()
	{
		Yii::$domain->lang->language->saveCurrent('en');
		expect('en')->equals(Yii::$app->language);
		
		Yii::$domain->lang->language->saveCurrent('ru');
		expect('ru')->equals(Yii::$app->language);
	}
	
	public function testSwitchInvalidLang()
	{
		Yii::$domain->lang->language->saveCurrent('zx');
		expect('ru')->equals(Yii::$app->language);
	}
	
	public function testList()
	{
		$collection = Yii::$domain->lang->language->all();
		$this->tester->assertCollection([
			[
				'code' => 'ru',
				'locale' => LanguageEnum::RU,
				'is_main' => true,
			],
			[
				'code' => 'en',
				'locale' => LanguageEnum::EN,
				'is_main' => false,
			],
			[
				'code' => 'xx',
				'locale' => LanguageEnum::SOURCE,
				'is_main' => false,
			],
		], $collection);
	}
	
	public function testListOrderByCode()
	{
		$query = Query::forge();
		$query->orderBy('code');
		$collection = Yii::$domain->lang->language->all($query);
		$this->tester->assertCollection([
			[
				'code' => 'en',
				'locale' => LanguageEnum::EN,
				'is_main' => false,
			],
			[
				'code' => 'ru',
				'locale' => LanguageEnum::RU,
				'is_main' => true,
			],
			[
				'code' => 'xx',
				'locale' => LanguageEnum::SOURCE,
				'is_main' => false,
			],
		], $collection);
	}
	
	public function testOneByLocale()
	{
		$expectEntity = [
			'code' => 'ru',
			'locale' => LanguageEnum::RU,
			'is_main' => true,
		];
		
		$collection = Yii::$domain->lang->language->oneByLocale('ru-RU');
		$this->tester->assertEntity($expectEntity, $collection);
		
		$collection = Yii::$domain->lang->language->oneByLocale('ru');
		$this->tester->assertEntity($expectEntity, $collection);
	}
	
}
