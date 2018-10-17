<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<?
/**
 * кешированный метод получения списка элементов инфоблока
 * тегированный кэш создается в /bitrix/cache/ в подпапке с названием метода класса
 * при добавлении/изменении/удалении элементов инфоблока в новом битриксе автоматически срабатывает нужно событие
 * поэтому дополнительно писать обработчки в init.php не нужно
 * author: ildar r. khasanshin .. 10021987.ru
 */
if ( ! CModule::IncludeModule("iblock") ) {
	die("module iblock not found");
};

class getListElementsIB {

	protected $cacheTime = "3600";
	protected $cacheParam = "getListEls";

	public function getListEls($sort, $select, $fields) {
		$data      = false;
		$obCache   = new CPHPCache;
		$cacheTime = $this->cacheTime;
		$cacheID   = md5($this->cacheParam);
		$cacheDir  = "/".$this->cacheParam;
		if ( $obCache->InitCache($cacheTime, $cacheID, $cacheDir) ) {
			$data = $obCache->GetVars();
		} elseif ( $obCache->StartDataCache() ) {
			global $CACHE_MANAGER;
			$CACHE_MANAGER->StartTagCache($cacheDir);
			$elList = CIBlockElement::GetList(
				$sort,
				$select,
				false,
				false,
				$fields
			);
			if ( $elList->SelectedRowsCount() > 0 ) {
				while ( $arElement = $elList->Fetch() ) {
					$data[] = $arElement;
				}
			}
			$CACHE_MANAGER->EndTagCache();
			$obCache->EndDataCache($data);
		}

		return $data;
	}

}

$els = new getListElementsIB();
$dat = $els->getListEls(
	array(
		"SORT" => "ASC"
	),
	array(
		"IBLOCK_ID" => 1,
		"ACTIVE"    => "Y"
	),
	array(
		"ID",
		"ACTIVE"
	)
);

foreach ( $dat as $val ) {
	echo $val["ID"]." ";
	echo $val["ACTIVE"]."<br>";
}