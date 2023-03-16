<div class="uk-card uk-card-body uk-padding-remove-vertical uk-background-muted uk-box-shadow-large" uk-sticky>
	<div class="uk-margin-small-top uk-flex-middle" uk-grid>
		<div class="uk-width-expand">
			<h4 class="">Update Catalog Bitrix24 after 1C Import:</h4>
		</div>
		<div class="uk-width-auto">
		<a href="#b24_bottom">
			<img uk-scroll="offset:300" class="uk-border-circle" width="25" height="25" src="../../_image/ric.png" alt="ric">
		</a>
		</div>
	</div>
		
	<div class="uk-margin-small uk-child-width-1-1 " uk-grid="">
		<div class="uk-inline">
			<div id="preloader" class="" hidden>
				<div class="uk-position-center">
					<span uk-spinner="ratio: .65"></span>
				</div>
			</div>
			<select id="selGET" class="uk-select  uk-form-small">
<option value=""></option>					
<option value="">&bull;CRM</option>

				<option value="crm.catalog.list">crm.catalog.list: Список товарных каталогов</option>
				<option value="crm.productsection.list">crm.productsection.list: Список разделов товаров</option>

				<option value="crm.currency.list">crm.currency.list: Список валют</option>
				<option value="crm.product.list">crm.product.list: Список продуктов</option>
				<option value="crm.product.property.enumeration.fields">crm.product.property.enumeration.fields: Описание полей элемента свойства товаров списочного типа</option>
				<option value="crm.product.property.list">crm.product.property.list: Список свойств товаров</option>
				<option value="crm.product.property.settings.fields">crm.product.property.settings.fields: Описание полей дополнительных настроек свойства товаров пользовательского типа</option>
				<option value="crm.product.property.types">crm.product.property.types: Список типов свойств товаров</option>

<option value=""></option>
<option value="">&bull;CATALOG</option>

				<option value="catalog.catalog.list">catalog.catalog.list: Список торговых каталогов</option>
				<option value="catalog.section.list">catalog.section.list: Список секций торговых каталогов</option>
				<option value="catalog.productPropertySection.list">catalog.productPropertySection.list: Список секционных настроек свойств товаров или торговых предложений</option>

				<option value="catalog.product.list">catalog.product.list: Список продуктов. (общий метод для всего - каталог товаров или торгоых предложений)</option>
				<option value="catalog.productProperty.list">catalog.productProperty.list: Список свойств товаров и торговых предложений</option>
				<option value="catalog.product.sku.list">catalog.product.sku.list: Cписок головных товаров (специализация по товарам имеющим торговые предложения - каталог товары)</option>
				<option value="catalog.product.offer.list">catalog.product.offer.list: Cписок торговых предложений (специализация по торговым предложениям - каталог торговые предложения)</option>
				<option value="catalog.product.service.list">catalog.product.service.list: Cписок услуг (специализация по услугам - каталог товары)</option>
				<option value="catalog.price.list">catalog.price.list: Cписок цен товаров и торговых предложений и услуг - специализации нет, любой каталог)</option> 
				<option value="catalog.vat.list">catalog.vat.list: Список ставок НДС</option>

<option value=""></option>
<option value="">&bull;Обработка каталога</option>
				<option value="updateCatalog">Обновить позиции каталога Наименование, Активность (без цен)</option>
				<option value="updateCurrency">Пересчитать валюты каталога по курсу и установить базовую цену (только валюта)</option>
				<option value="updateAll">Обновить и каталог и все цены</option>
			</select>
		</div>
	</div>
	
	<div class="uk-margin-small uk-child-width-1-2" uk-grid="">
		<div class="uk-width-3-5">
			<div class="uk-margin-small uk-flex uk-flex-middle">
				<div class="uk-form-label uk-first-column uk-width-2-5">
					<span>Метод</span>
					<select id="typeSelect" class="uk-select uk-form-width-small  uk-form-small" disabled uk-tooltip="title: тип запроса">
   						<option value="list">промис</option>
						<option value="fetch">генератор</option>
					</select>
				</div>
						
				<div class="uk-form-label uk-form-controls uk-padding-remove uk-width-2-5">
					<span>Каталог</span>
					<select id="catalog" class="uk-select uk-form-width-small  uk-form-small" disabled uk-tooltip="title: каталог">
    					<option value="21">product</option>
   						<option value="46">offer</option>
					</select>
				</div>
			</div>	
		</div>	
		<div class="uk-width-2-5 uk-flex uk-flex-right uk-flex-middle">
			<div class="uk-form-controls">
				<button id="get" class="uk-button uk-button-primary uk-button-small" type="button" disabled  uk-tooltip="title: выполнить запрос к Б24">getData</button>
			</div>	
			<div class="uk-form-controls uk-margin-left">
				<button id="update" class="uk-button uk-button-primary uk-button-small" type="button" disabled  uk-tooltip="title: Обновить каталог Б24">Update Catalog</button>
			</div>	
		</div>	
	</div>	
				
	<progress id="js-progressbar" class="uk-progress uk-margin-small-top uk-margin-remove-bottom" value="0" max="100"></progress>
	<span id="comment" class="uk-text-meta">выберите действие...</span>
</div>

<div class="uk-placeholder uk-margin-remove-top uk-padding-remove">
	<!--div id="preloader"  hidden><span uk-spinner>...ждите</span></div-->
	<div id='outShow'>
	</div>
</div>

