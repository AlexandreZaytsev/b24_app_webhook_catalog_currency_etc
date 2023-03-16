<div class="uk-card uk-card-body uk-padding-remove-vertical uk-background-muted uk-box-shadow-large" uk-sticky>
	<div class="uk-margin-small-top uk-flex-middle" uk-grid>
		<div class="uk-width-expand">
			<h4 class="">REST API request Data Bitrix24:</h4>
		</div>
		<div class="uk-width-auto">
		<a href="#b24_bottom">
			<img uk-scroll="offset:300" class="uk-border-circle" width="25" height="25" src="../../_image/ric.png" alt="ric">
		</a>
		</div>
	</div>
			
	<div class="uk-margin-small uk-child-width-1-1" uk-grid="">
		<div class="uk-width-4-5">
			<div class="uk-margin-small uk-flex uk-flex-middle uk-child-width-expand" uk-grid="">
				<div class="uk-inline uk-width-1-1">
					<div id="preloader" class="" hidden>
						<div class="uk-position-center">
							<span uk-spinner="ratio: .65"></span>
						</div>
					</div>
				<select id="selGET" class="uk-select uk-form-small">
	    					<option value="">выберите запрос..</option>
<option value=""></option>						
	    					<option value="">Общие методы:</option>
							<option value="methods">&nbsp;&nbsp;methods: Список методов, доступных текущему приложению</option>
							<option value="scope">&nbsp;&nbsp;scope: Все разрешения, доступные для данного приложения</option>
							<option value="app.info">&nbsp;&nbsp;app.info: Информации о приложении</option>
	    					<!--option value="feature.get">&nbsp;&nbsp;Информация о доступности функционала на портале</option-->
	    					<option value="profile">&nbsp;&nbsp;profile: Информация о текущем пользователе (без скоупов)</option>
	    					<option value="server.time">&nbsp;&nbsp;server.time: Текущее время сервера</option>
<option value=""></option>	    					
	    					<option value="">&nbsp;&nbsp;Методы событий:</option>
	    					<option value="events">&nbsp;&nbsp;&nbsp;&nbsp;event: Общий список событий</option>
	    					<option value="event.offline.list">&nbsp;&nbsp;&nbsp;&nbsp;event.offline.list: Текущая очередь событий</option>
<option value=""></option>
		  					<option value="">Работа с пользователями:</option>
							<option value="user.fields">&nbsp;&nbsp;user.fields: Список полей пользователя</option>
							<option value="user.get">&nbsp;&nbsp;user.get: Пользователь по идентификатору {}</option>
							<option value="user.current">&nbsp;&nbsp;user.current: Информация о текущем пользователе</option>
	    					<option value="">&nbsp;&nbsp;Пользовательские поля:</option>
	    					<option value="user.userfield.list">&nbsp;&nbsp;&nbsp;&nbsp;user.userfield.list: Список пользовательских полей {}</option>
<option value=""></option>
		  					<option value="">Работа с подразделениями:</option>
							<option value="department.fields">&nbsp;&nbsp;department.fields: Cписок названий полей подразделения</option>
							<option value="department.get">&nbsp;&nbsp;department.get: Список подразделений по фильтру {}</option>
<option value=""></option>
	    					<option value="">CRM:</option>
<option value="">---------------------------------------------------------------------------------------------------------------------</option>
	    					<option value="">&nbsp;&nbsp;Компании:</option>
	    					<option value="crm.company.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.company.fields: Описание полей (в том числе пользовательских)</option>
	    					<option value="crm.company.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.company.get: Компания по идентификатору (id)</option>
	    					<option value="crm.company.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.company.list: Список компаний по фильтру {}</option>
	    					<option value="crm.company.userfield.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.company.userfield.get: Пользовательское поле компаний по идентификатору (id)</option>
	    					<option value="crm.company.userfield.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.company.userfield.list: Список пользовательских полей компаний по фильтру {}</option>
	    					<option value="crm.company.contact.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.company.contact.fields: Связи компания-контакт описание полей</option>
	    					<option value="crm.company.contact.items.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.company.contact.items.get: Список контактов, связанных с указанной (id) компанией</option>
<option value=""></option>	    					
	    					<option value="">&nbsp;&nbsp;Контакты:</option>
							<option value="crm.contact.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.contact.fields: Контакты.Поля, в том числе пользовательские</option>
							<option value="crm.contact.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.contact.get: Контакт по идентификатору (id)</option>
							<option value="crm.contact.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.contact.list: Список контактов по фильтру</option>
	    					<option value="crm.contact.userfield.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.contact.get: Пользовательское поле контакта по идентификатору (id)</option>
	    					<option value="crm.contact.userfield.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.contact.list: Список пользовательских полей контакта по фильтру</option>
	    					<option value="crm.contact.company.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.company.fields: Связи контакт-компания описание полей</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Лиды:</option>
							<option value="crm.lead.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.lead.fields: Описание полей (в том числе пользовательских)</option>
							<option value="crm.lead.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.lead.get: Лид по идентификатору (id)</option>
							<option value="crm.lead.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.lead.list: Список лидов по фильтру {}</option>
							<option value="crm.lead.userfield.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.lead.userfield.get: Пользовательское поле лида по идентификатору (id)</option>
							<option value="crm.lead.userfield.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.lead.userfield.list: Список пользовательских полей по фильтру {}</option>
							<option value="crm.lead.productrows.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.lead.productrows.get: Товарные позиции по идентификатору лида (id)</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Сделки:</option>
							<option value="crm.deal.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.deal.fields: Описание полей (в том числе пользовательских)</option>
							<option value="crm.deal.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.deal.list: Список сделок по фильтру {}</option>
							<option value="crm.deal.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.deal.get: Сделка по идентификатору (id)</option>
							<option value="crm.deal.contact.items.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.deal.contact.items.get: Набор контактов связанных со сделкой (id)</option>
							<option value="crm.deal.contact.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.deal.contact.fields: Связи сделка-контакт описание полей</option>
							<option value="crm.deal.productrows.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.deal.productrows.get: Список товарные позиции сделки (id)</option>
							<option value="crm.deal.userfield.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.deal.userfield.get: Пользовательское поле сделок по идентификатору (id)</option>
							<option value="crm.deal.userfield.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.deal.userfield.list: Список пользовательских полей сделок по фильтру {}</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Коммерческие предложения:</option>
							<option value="crm.quote.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.quote.fields: Описание полей (в том числе пользовательских)</option>
							<option value="crm.quote.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.quote.list: Список КП по фильтру {}</option>
							<option value="crm.quote.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.quote.get: КП по идентификатору (id)</option>
							<option value="crm.quote.productrows.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.quote.productrows.get: Список товарные позиции КП (id)</option>
							<option value="crm.quote.userfield.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.quote.userfield.get: Пользовательское поле КП по идентификатору (id)</option>
							<option value="crm.quote.userfield.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.quote.userfield.list: Список пользовательских полей КП по фильтру {}</option>
<option value=""></option>	    
	    					<option value="">&nbsp;&nbsp;Каталог:</option>
							<option value="crm.catalog.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.catalog.fields: Описание полей каталога товаров</option>
							<option value="crm.catalog.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.catalog.list: Список товарных каталогов по фильтру {}</option>
							<option value="crm.catalog.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.catalog.get: Товарный каталог по идентификатору (id)</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Валюты:</option>
							<option value="crm.currency.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.currency.fields: Описание полей валюты</option>
							<option value="crm.currency.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.currency.list: Список валют ("filter", "select" и "navigation" не поддерживаются)}</option>
							<option value="crm.currency.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.currency.get: Валюта по идентификатору (id)</option>
<option value=""></option>							
	    					<option value="">&nbsp;&nbsp;Разделы товаров:</option>
							<option value="crm.productsection.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.productsection.fields: Описание полей раздела товаров</option>
							<option value="crm.productsection.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.productsection.list: Список разделов товаров по фильтру {}</option>
							<option value="crm.productsection.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.productsection.get: Раздел товаров по идентификатору (id)</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Товарные позиции:</option>
							<option value="crm.item.productrow.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.item.productrow.fields: Описание полей товарных позиций</option>
							<option value="crm.item.productrow.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.item.productrow.list: Массив товарных позиций</option>
							<option value="crm.item.productrow.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.item.productrow.get: Товарная позиция по идентификатору (id)</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Товары:</option>
							<option value="crm.product.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.product.fields: Описание полей товара</option>
							<option value="crm.product.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.product.list: Список товаров по фильтру {}</option>
							<option value="crm.product.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.product.get: Товар по идентификатору (id)</option>
							<option value="crm.product.property.enumeration.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.product.property.enumeration.fields: Описание полей элемента свойства товаров списочного типа</option>
							<option value="crm.product.property.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.product.property.fields: Описание полей для свойств товаров</option>
							<option value="crm.product.property.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.product.property.list: Список свойств товаров по фильтру {}</option>
							<option value="crm.product.property.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.product.property.get: Свойство товара по идентификатору (id)</option>
							<option value="crm.product.property.settings.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.product.property.settings.fields: Описание полей дополнительных настроек свойства товаров пользовательского типа</option>
							<option value="crm.product.property.types">&nbsp;&nbsp;&nbsp;&nbsp;crm.product.property.types: Список типов свойств товаров</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Пользовательские поля:</option>
							<option value="crm.userfield.enumeration.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.userfield.enumeration.fields: Возвращает описание полей для пользовательского поля типа "enumeration" (список)</option>
							<option value="crm.userfield.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.userfield.fields: Возвращает описание полей для пользовательских полей</option>
							<option value="crm.userfield.settings.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.userfield.settings.fields: Возвращает описание полей настроек для типа пользовательского поля</option>
							<option value="crm.userfield.types">&nbsp;&nbsp;&nbsp;&nbsp;crm.userfield.types: Возвращает список типов пользовательских полей</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Вспомогательные сущности:</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Дубликаты:</option>
							<option value="crm.duplicate.findbycomm">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.duplicate.findbycomm: Идентификаторы лидов, контактов и компаний содержащих телефоны или email-адреса из заданного списка</option>
							<!--option value="crm.entity.mergeBatch">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.entity.mergeBatch: Объединение дубликатов</option-->
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Множественные поля:</option>
							<option value="crm.multifield.fields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.multifield.fields: Описание множественных полей</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Перечисления:</option>
							<option value="crm.enum.fields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.enum.fields: Описание полей перечисления</option>
							<option value="crm.enum.activitydirection">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.enum.activitydirection: Элементы перечисления "Направление активности" (для писем и звонков)</option>
							<option value="crm.enum.activitynotifytype">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.enum.activitynotifytype: Элементы перечисления "Тип уведомления о начале активности" (для встреч и звонков)</option>
							<option value="crm.enum.activitypriority">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.enum.activitypriority: Элементы перечисления "Приоритет активности"</option>
							<option value="crm.enum.activitystatus">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.enum.activitystatus: Элементы перечисления "Статус"</option>
							<option value="crm.enum.activitytype">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.enum.activitytype: Элементы перечисления "Тип активности"</option>
							<option value="crm.enum.addresstype">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.enum.addresstype: Элементы перечисления "Тип адреса"</option>
							<option value="crm.enum.contenttype">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.enum.contenttype: Элементы перечисления "Тип содержания"</option>
							<option value="crm.enum.getorderownertypes">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.enum.getorderownertypes: Идентификаторы типов сущностей, к которым доступна привязка заказа</option>
							<option value="crm.enum.ownertype">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.enum.ownertype: Идентификаторы типов сущностей CRM и смарт-процессов</option>
							<option value="crm.enum.settings.mode">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.enum.settings.mode: Описание режимов работы CRM</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Справочники:</option>
							<option value="crm.status.fields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.status.fields: Описание полей справочника</option>
							<option value="crm.status.entity.types">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.status.entity.types: Описание типов справочников</option>
							<!--option value="crm.status.entity.items">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.status: CRM.Вспомогательные сущности.Справочники.Элементы справочников</option-->
							<option value="crm.status.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.status.get: Элемент справочника по идентификатору (id)</option>
							<option value="crm.status.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.status.list: Cписок элементов справочника по фильтру</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Ставки НДС:</option>
	    					<option value="crm.vat.fields">&nbsp;&nbsp;&nbsp;&nbsp;crm.vat.fields: Описание полей ставки НДС</option>
	    					<option value="crm.vat.get">&nbsp;&nbsp;&nbsp;&nbsp;crm.vat.get: Ставка НДС по идентификатору (id)</option>
	    					<option value="crm.vat.list">&nbsp;&nbsp;&nbsp;&nbsp;crm.vat.list: Список ставок НДС по фильтру {}</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Реквизиты:</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Адрес:</option>					
							<option value="crm.address.fields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.address.fields: Описание полей адреса</option>
							<option value="crm.address.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.address.list: Список адресов по фильту {}</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Реквизиты:</option>							
							<option value="crm.requisite.fields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisit.fieldse: Описание полей реквизита</option>
							<option value="crm.requisite.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.list: Список реквизитов по фильтру {}</option>
							<option value="crm.requisite.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.get: Реквизит по идентификатору (id)</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Банковские реквизиты:</option>
							<option value="crm.requisite.bankdetail.fields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.bankdetail.fields: Описание полей банковских реквизитов</option>
							<option value="crm.requisite.bankdetail.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.bankdetail.list: Список банковских реквизитов по фильтру {}</option>
							<option value="crm.requisite.bankdetail.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.bankdetail.get: Банковский реквизит по идентификатору (id)</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Связи реквизитов:</option>
							<option value="crm.requisite.link.fields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.link.fields: Описание полей связей реквизитов</option>
							<option value="crm.requisite.link.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.link.list: Список связей реквизитов</option>
							<option value="crm.requisite.link.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.link.get: Связь реквизитов с Сущностью</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Поля реквизитов:</option>
							<option value="crm.requisite.preset.fields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.preset.fields: Описание полей шаблона реквизитов</option>
							<option value="crm.requisite.preset.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.preset.list: Список шаблонов реквизитов по фильтру {}</option>
							<option value="crm.requisite.preset.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.preset.get: Шаблон реквизита по идентификатору (id)</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Наборы полей реквизитов:</option>
							<option value="crm.requisite.preset.field.fields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.preset.field.fields: Описание набора полей шаблона реквизитов</option>
							<option value="crm.requisite.preset.field.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.preset.field.list: Список полей из набора полей шаблона для определенного реквизита по фильтру {}</option>
							<option value="crm.requisite.preset.field.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.preset.field.get: Описание поля из набора полей шаблона для определенного реквизита по идентификатору (id)</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Пользовательские поля реквизитов:</option>
							<option value="crm.requisite.userfield.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.userfield.list: Список пользовательских полей реквизита по фильтру {}</option>
							<option value="crm.requisite.userfield.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.requisite.userfield.get: Пользовательское поле реквизита по идентификатору по идентификатору (id</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Режим работы CRM:</option>
							<option value="crm.settings.mode.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.settings.mode.get: Текущие настройки режима работы CRM</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Генератор документов:</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Документы:</option>					
							<option value="crm.documentgenerator.document.getfields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.documentgenerator.document.getfields: Список полей документа с их описанием</option>
							<option value="crm.documentgenerator.document.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.documentgenerator.document.get: Информация о документе по его идентификатору (id)</option>
							<option value="crm.documentgenerator.document.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.documentgenerator.document.list: Cписок документов по фильтру {}</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Шаблоны документов:</option>					
							<option value="crm.documentgenerator.template.getfields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.documentgenerator.template.getfields: Список полей шаблона с их описанием</option>
							<option value="crm.documentgenerator.template.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.documentgenerator.template.get: Информация о шаблоне по его идентификатору (id)</option>
							<option value="crm.documentgenerator.template.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.documentgenerator.template.list: Cписок шаблонов по фильтру {}</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Нумераторы:</option>					
							<option value="crm.documentgenerator.numerator.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.documentgenerator.numerator.get: Информация о нумераторе  по его идентификатору (id)</option>
							<option value="crm.documentgenerator.numerator.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.documentgenerator.numerator.list: Cписок нумераторов</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Смарт-процессы:</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Методы настроек смарт-процессов:</option>
							<option value="crm.type.fields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.type.fields: Информация о собственных полях настроек смарт-процесса</option>
							<option value="crm.type.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.type.get: Информация о смарт-процессе по его идентификатору (id)</option>
							<option value="crm.type.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.type.list: Массив настроек смарт-процессов</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Направления смарт-процессов:</option>
							<option value="crm.category.fields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.category.fields: Информация о полях направлений</option>
							<option value="crm.category.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.category.get: Информацию о направлении с идентификатором id</option>
							<option value="crm.category.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.category.list: Массив направлений, которые относятся к типу сущности с идентификатором entityTypeId</option>
	    					
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Элементы смарт-процессов:</option>
							<option value="crm.item.fields">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.item.fields: Информация о полях смарт-процесса с идентификатором entityTypeId</option>
							<option value="crm.item.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.item.get: Иинформация об элементе с идентификатором id смарт-процесса с идентификатором entityTypeId</option>
							<option value="crm.item.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;crm.item.list: Массив элементов смарт-процесса с идентификатором entityTypeId</option>

<option value=""></option>
	    					<option value="">Настройки пользовательских полей:</option>
<option value="">---------------------------------------------------------------------------------------------------------------------</option>
	    					<option value="">&nbsp;&nbsp;Работа с пользовательскими полями:</option>
	    					<option value="userfieldconfig.get">&nbsp;&nbsp;&nbsp;&nbsp;userfieldconfig.get: Вернет данные о настройках пользовательского поля с идентификатором id.</option>
	    					<option value="userfieldconfig.getTypes">&nbsp;&nbsp;&nbsp;&nbsp;userfieldconfig.getTypes: Вернет набор доступных типов пользовательских полей для модуля moduleId</option>
	    					<option value="userfieldconfig.getTypes">&nbsp;&nbsp;&nbsp;&nbsp;userfieldconfig.getTypes: Вернет набор доступных типов пользовательских полей для модуля moduleId</option>
	    					<option value="userfieldconfig.list">&nbsp;&nbsp;&nbsp;&nbsp;userfieldconfig.list: Вернет список настроек пользовательских полей</option>




<option value=""></option>
	    					<option value="">Информационные блоки</option>
<option value="">---------------------------------------------------------------------------------------------------------------------</option>
							<option value="">Торговый каталог:</option>
<option value=""></option>							
	    					<option value="">&nbsp;&nbsp;Торговый каталог:</option>
	    					<option value="catalog.catalog.getFields">&nbsp;&nbsp;&nbsp;&nbsp;catalog.catalog.getFields: Описание полей торгового каталога</option>
	    					<option value="catalog.catalog.get">&nbsp;&nbsp;&nbsp;&nbsp;catalog.catalog.get: Поля торгового каталога по идентификатору (id)</option>
	    					<option value="catalog.catalog.list">&nbsp;&nbsp;&nbsp;&nbsp;catalog.catalog.list: Список торговых каталогов по фильтру {}</option>
	    					<option value="catalog.catalog.isOffers">&nbsp;&nbsp;&nbsp;&nbsp;catalog.catalog.isOffers: Проверка торгового каталога (id) на признак каталога товарных предложений</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Цена:</option>
	    					<option value="catalog.price.getFields">&nbsp;&nbsp;&nbsp;&nbsp;catalog.price.getFields: Описание полей цены товара</option>
	    					<option value="catalog.price.get">&nbsp;&nbsp;&nbsp;&nbsp;catalog.price.get: Поля цены товара по идентификатору (id)</option>
	    					<option value="catalog.price.list">&nbsp;&nbsp;&nbsp;&nbsp;catalog.price.list: Список цен товаров по фильтру {}</option>
<option value=""></option>				
	    					<option value="">&nbsp;&nbsp;Тип цены:</option>
	    					<option value="catalog.priceType.getFields">&nbsp;&nbsp;&nbsp;&nbsp;catalog.priceType.getFields: Описание полей типа цены товара</option>
	    					<option value="catalog.priceType.get">&nbsp;&nbsp;&nbsp;&nbsp;catalog.priceType.get: Поля типа цены товара по идентификатору (id)</option>
	    					<option value="catalog.priceType.list">&nbsp;&nbsp;&nbsp;&nbsp;catalog.priceType.list: Список типов цен товаров по фильтру {}</option>
<option value=""></option>								
	    					<option value="">&nbsp;&nbsp;Товар:</option>
	    					<option value="catalog.product.getFieldsByFilter">&nbsp;&nbsp;&nbsp;&nbsp;catalog.product.getFieldsByFilter: Описание полей торгового каталога по фильтру</option>
	    					<option value="catalog.product.get">&nbsp;&nbsp;&nbsp;&nbsp;catalog.product.get: Поля товара торгового каталога по идентификатору (id)</option>
	    					<option value="catalog.product.list">&nbsp;&nbsp;&nbsp;&nbsp;catalog.product.list: Список товаров торгового каталога по фильтру {}</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Товары с торговыми предложениями: головные товары:</option>
	    					<option value="catalog.product.sku.getFieldsByFilter">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;catalog.product.sku.getFieldsByFilter: Описание полей головного товара по фильтру</option>
	    					<option value="catalog.product.sku.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;catalog.product.sku.get: Поля головного товара по идентификатору (id)</option>
	    					<option value="catalog.product.sku.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;catalog.product.sku.list: Список готовных товаров по фильтру {}</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Товары с торговыми предложениями: предложения</option>
	    					<option value="catalog.product.offer.getFieldsByFilter">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;catalog.product.offer.getFieldsByFilter: Описание полей торгового предложения по фильтру</option>
	    					<option value="catalog.product.offer.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;catalog.product.offer.get: Поля торгового предложения по идентификатору (id)</option>
	    					<option value="catalog.product.offer.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;catalog.product.offer.list: Список торговых предложений по фильтру {}</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;&nbsp;&nbsp;Услуги</option>
	    					<option value="catalog.product.service.getFieldsByFilter">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;catalog.product.service.getFieldsByFilter: Описание полей услуги по фильтру</option>
	    					<option value="catalog.product.service.get">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;catalog.product.service.get: Поля услуги по идентификатору (id)</option>
	    					<option value="catalog.product.service.list">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;catalog.product.service.list: Список услуг по фильтру {}</option>
<option value=""></option>	
	    					<option value="">&nbsp;&nbsp;Свойства товаров и торговых предложений:</option>
	    					<option value="catalog.productProperty.getFields">&nbsp;&nbsp;&nbsp;&nbsp;catalog.productProperty.getFields: Описание свойств товаров и торговых предложений</option>
	    					<option value="catalog.productProperty.get">&nbsp;&nbsp;&nbsp;&nbsp;catalog.productProperty.get: Поля свойств товара и торговых предложений по идентификатору (id)</option>
	    					<option value="catalog.productProperty.list">&nbsp;&nbsp;&nbsp;&nbsp;catalog.productProperty.list: Список свойств товаров и торговых предложений по фильтру {}</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Секционные настройки свойств:</option>
	    					<option value="catalog.productPropertySection.get">&nbsp;&nbsp;&nbsp;&nbsp;catalog.productPropertySection.get: Значения секционных настроек свойства товаров или торговых предложений (propertyId)</option>
	    					<option value="catalog.productPropertySection.list">&nbsp;&nbsp;&nbsp;&nbsp;catalog.productPropertySection.list: Список секционных настроек свойств товаров или торговых предложений по фильтру</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Секция каталога:</option>
	    					<option value="catalog.section.getFields">&nbsp;&nbsp;&nbsp;&nbsp;catalog.section.getFields: Поля секции торгового каталога</option>
	    					<option value="catalog.section.get">&nbsp;&nbsp;&nbsp;&nbsp;catalog.section.get: Значения полей секции торгового каталога по ID</option>
	    					<option value="catalog.section.list">&nbsp;&nbsp;&nbsp;&nbsp;catalog.section.list: Список секций торговых каталогов по фильтру</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Ставки НДС:</option>
	    					<option value="catalog.vat.getFields">&nbsp;&nbsp;&nbsp;&nbsp;catalog.vat.getFields: Поля ставки НДС</option>
	    					<option value="catalog.vat.get">&nbsp;&nbsp;&nbsp;&nbsp;catalog.vat.get: Значения полей ставки НДС по ID</option>
	    					<option value="catalog.vat.list">&nbsp;&nbsp;&nbsp;&nbsp;catalog.vat.list: Список ставок НДС по фильтру</option>
<option value=""></option>
							<option value="">Универсальные списки:</option>
<option value=""></option>							
	    					<option value="">&nbsp;&nbsp;Работа со списками:</option>
	    					<option value="lists.get">&nbsp;&nbsp;&nbsp;&nbsp;lists.get: Возвращает данные инфоблока</option>
	    					<option value="lists.get.iblock.type.id">&nbsp;&nbsp;&nbsp;&nbsp;lists.get.iblock.type.id: Возвращает id типа инфоблока</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Работа с элементами списка:</option>
	    					<option value="lists.element.get">&nbsp;&nbsp;&nbsp;&nbsp;lists.element.get: Возвращает список элементов или элемент</option>
	    					<option value="lists.element.get.file.url">&nbsp;&nbsp;&nbsp;&nbsp;lists.element.get.file.url: Возвращает путь к файлу</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Работа с полями списка:</option>
	    					<option value="lists.field.get">&nbsp;&nbsp;&nbsp;&nbsp;lists.field.get: Возвращает данные поля</option>
	    					<option value="lists.field.type.get">&nbsp;&nbsp;&nbsp;&nbsp;lists.field.type.get: озвращает доступные типа полей для указанного списка</option>
<option value=""></option>
	    					<option value="">&nbsp;&nbsp;Работа с разделами списка:</option>
	    					<option value="lists.section.get">&nbsp;&nbsp;&nbsp;&nbsp;lists.section.get: Возвращает список разделов или раздел</option>
<option value=""></option>






						</select>
						</div>
						<!--select id="selGET" class="uk-select uk-width-2-3">
	    					<option value="">выберите запрос..</option>
	    				</select-->	
					</div>
				</div>
				<div class="uk-width-1-5 uk-padding-remove-left">
					<div class="uk-margin-small uk-child-width-1-2" uk-grid="">
						<div>
							<select id="first50" class="uk-form-small uk-select uk-form-width-large" uk-tooltip="title: one - первые 50 записей, list - все записи">
								<option value="one">one</option>
								<option value="list">list</option>
							</select>
						</div>
						<div class="uk-padding-remove">
							<select id="typeSelect" class="uk-form-small uk-select uk-form-width-large" disabled uk-tooltip="title: тип запроса обработки запроса промис или генератор">
   								<option value="list">promice</option>
								<option value="fetch">generator</option>
							</select>
						</div>
					</div>
		</div>
			</div>	

	<div class="uk-margin-small uk-child-width-1-2" uk-grid="">
		<div class="uk-width-4-5">
			<div class="uk-margin-small uk-flex uk-flex-middle uk-child-width-expand" uk-grid="">
					
				<!--div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
					<label><input class="uk-radio" type="radio" name="radio2" checked> id</label>
					<label><input class="uk-radio" type="radio" name="radio2"> preset</label>
					<label><input class="uk-radio" type="radio" name="radio2"> filter</label>
					<label><input class="uk-radio" type="radio" name="radio2"> nothing</label>
				</div-->
					
				<div class="uk-first-column uk-flex-right uk-margin-left">
					<div class="uk-form-label"><input id = "chID" class="uk-checkbox" type="checkbox" uk-tooltip="title: использовать идентификатор для get запросов" > GetID</div>
				</div>
				<div class="uk-form-controls uk-padding-remove">
	            	<input id="valID" class="uk-input uk-form-small" type="text" placeholder="id" uk-tooltip="title: значение идентификатора">
				</div>
				<div class="uk-first-column">
					<div class="uk-form-label"><input id = "chFilter"  class="uk-checkbox" type="checkbox" uk-tooltip="title: использовать фильтр по свойствам"> Фильтр</div>
				</div>
				<div class="uk-form-controls uk-padding-remove">
					<select id="maskFilter" class="uk-select uk-form-width-xsmall uk-form-small" uk-tooltip="title: маска фильтра">
   						<option value=""></option>
						<option value="?">?</option>
						<option value="%">%</option>
						<option value="!%">!%</option>
						<option value="><">&gt;&lt;</option>
						<option value="!><">!&gt;&lt;</option>
						<option value="=">=</option>
						<option value=">">&gt;</option>
						<option value="<">&lt;</option>
						<option value=">=">&gt;=</option>
						<option value="<=">&lt;=</option>
					</select>					
				</div>					
				<div class="uk-form-controls uk-padding-remove">
					<select id="nameFilter" class="uk-select uk-form-width-large uk-form-small" uk-tooltip="title: имя свойства">
    					<option value="*">*</option>
    					<option value="ID">ID</option>
    					<option value="TITLE">TITLE</option>
    					<option value="ACTIVE">ACTIVE</option>
    					<option value="EMAIL">EMAIL</option>
    					<option value="NAME">NAME</option>
    					<option value="FIELD_NAME">FIELD_NAME</option>
    					<option value="PARENT">PARENT</option>
    					<option value="UF_*">UF_*</option>
						<option value="CURRENCY">CURRENCY</option>
						<option value="id">id</option>
						<option value="productId">productId</option>
						<option value="ANCHOR_ID">ANCHOR_ID</option> 
						<option value="ENTITY_ID">ENTITY_ID</option>
						<option value="IBLOCK_CODE">IBLOCK_CODE</option>
						<option value="IBLOCK_ID">IBLOCK_ID</option>
						<option value="IBLOCK_TYPE_ID">IBLOCK_TYPE_ID</option>
						<option value="entityTypeId">entityTypeId</option>
						<option value="catalogGroupId">catalogGroupId</option>
					</select>					
				</div>					
				<div class="uk-form-controls uk-padding-remove">
		            <input id="valFilter" class="uk-input uk-form-small" id="form-horizontal-text" type="text" placeholder="значение"  uk-tooltip="title: значение свойства">
				</div>
			</div>
		</div>	
    	<div class="uk-width-1-5 uk-flex uk-flex-bottom uk-flex-right">
			<button class="uk-button uk-button-primary uk-button-small" type="button" disabled  uk-tooltip="title: выполнить запрос к Б24">getData</button>
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

