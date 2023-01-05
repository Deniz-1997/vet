<template>
  <v-row>
    <v-col class="shw-prt-blc" cols="12">
      <v-row justify="end">
        <v-col cols="5">
          <!--            <p>Приложение №2 <br>к постановлению Правительства<br> Российской Федерации</p>-->
        </v-col>
      </v-row>
      <v-row justify="end">
        <v-col cols="5">
          <!--            <p>от______________2021 г. №_____</p>-->
        </v-col>
      </v-row>
      <v-row>
        <v-col class="d-flex justify-center" cols="12">
          <img :src="qrLink" class="mr-10" />
          <h6 class="font-weight-bold">
            ТОВАРОСОПРОВОДИТЕЛЬНЫЙ ДОКУМЕНТ НА ПАРТИЮ ЗЕРНА ИЛИ ПАРТИЮ ПРОДУКТОВ ПЕРЕРАБОТКИ ЗЕРНА
          </h6>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <table style="table-layout: fixed">
            <tr style="border: 0">
              <td class="text-center" colspan="6"></td>
              <td class="text-center" colspan="7" style="border: 1px solid black">
                Номер СДИЗ
                {{ model.sdiz_number !== undefined ? model.sdiz_number : model.sdiz_gpb_number }}
              </td>
            </tr>
            <template v-if="model.getObjectLot().number_type === 'lot_number'">
              <tr>
                <td class="text-center" colspan="13">1.1 Сведения о партии зерна</td>
              </tr>
              <tr>
                <!--                    <td colspan="13">1.1.1 Номер партии</td>-->
                <td colspan="13">1.1.1 Номер партии: {{ model.objects.lot.getNumber() }}</td>
              </tr>
              <tr>
                <td colspan="13">1.1.2 Вид сельскохозяйственной культуры</td>
                <!--                    <td colspan="13">1.1.2. {{ }}</td>-->
              </tr>
              <tr>
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">
                  1.1.2.1 Код из справочника ОКПД 2:
                  {{ model.objects.lot.objects.okpd2.code }}
                </td>
              </tr>
              <tr>
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">
                  1.1.2.2 Код из справочника ТН ВЭД ЕАЭС:
                  {{ tnvedById ? tnvedById.tnved : '—' }}
                </td>
              </tr>
              <tr>
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">1.1.2.3 Наименование: {{ model.objects.lot.objects.okpd2.name }}</td>
              </tr>
              <tr>
                <td colspan="13">1.1.3 Год(-ы) урожая: {{ model.objects.lot.lot_year }}</td>
              </tr>
              <tr>
                <td colspan="13">1.1.4 Цель использования: {{ model.objects.lot.objects.target.name }}</td>
              </tr>
              <tr>
                <td colspan="13">1.1.5 Назначение: {{ model.objects.lot.objects.purpose.name }}</td>
              </tr>
              <tr>
                <td colspan="13">1.1.6 Масса (нетто в килограммах): {{ model.objects.lot.amount_kg_original }}</td>
              </tr>
              <tr>
                <td colspan="13">
                  1.1.7 Номер документа о результатах государственного мониторинга зерна:
                  {{ model.objects.lot.objects.laboratory_monitor.laboratory_monitor_number
                  }}<span v-if="!model.objects.lot.research_numbers_government_monitoring_id"> —</span>
                </td>
              </tr>
              <tr>
                <td colspan="13">
                  1.1.8 Сведения о предшествующей(-их) партии(-ях)
                  <span v-if="model.objects.lot.objects.lots_moved.length === 0">: —</span>
                </td>
              </tr>
              <tr v-for="(a, i) in model.objects.lot.objects.lots_moved" :key="i">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="2">1.1.8.{{ ++i }}</td>
                <td colspan="5">{{ a.lot_number }}</td>
                <td colspan="5">{{ a.value }} кг</td>
              </tr>
              <tr>
                <td colspan="13">
                  1.1.9 Потребительские свойства партии:
                  <span v-if="model.objects.lot.objects.quality_indicators.length === 0">: —</span>
                </td>
              </tr>
              <tr v-for="(a, i) in model.objects.lot.objects.quality_indicators" :key="i">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="2">1.1.9.{{ ++i }}</td>
                <td colspan="4">{{ a.name }}</td>
                <td colspan="3">{{ a.value }}</td>
                <!--                <td colspan="3">{{ /\((.*)\)/.exec(a.quality_indicators)[1] }}</td>-->
                <td colspan="3">{{ a.measure }}</td>
              </tr>
              <tr>
                <td colspan="13">
                  1.1.10 Страна происхождения:
                  {{
                    model.objects.lot.origin_location !== null ? model.objects.lot.origin_location['address'] : 'Россия'
                  }}
                </td>
              </tr>
              <tr>
                <td colspan="13">
                  1.1.11 Декларация о соответствии<span
                    v-if="!model.objects.lot.objects.docs.filter((v) => v.type_id === 1).length"
                    >: —</span
                  >
                </td>
              </tr>

              <tbody v-if="model.objects.lot.objects.docs.filter((v) => v.type_id === 1).length">
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.1.11.1 Дата: {{ getDateFromDocs(1, 'date') }}</td>
                </tr>
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.1.11.2 Номер: {{ getDateFromDocs(1, 'number') }}</td>
                </tr>
              </tbody>
              <tr>
                <td colspan="13">
                  1.1.12 Фитосанитарный сертификат<span
                    v-if="!model.objects.lot.objects.docs.filter((v) => v.type_id === 2).length"
                    >: —</span
                  >
                </td>
              </tr>

              <tbody v-if="model.objects.lot.objects.docs.filter((v) => v.type_id === 2).length">
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.1.12.1 Дата: {{ getDateFromDocs(2, 'date') }}</td>
                </tr>
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.1.12.2 Номер: {{ getDateFromDocs(2, 'number') }}</td>
                </tr>
              </tbody>
              <tr>
                <td colspan="13">
                  1.1.13 Ветеринарный сертификат<span
                    v-if="!model.objects.lot.objects.docs.filter((v) => v.type_id === 3).length"
                    >: —</span
                  >
                </td>
              </tr>

              <tbody v-if="model.objects.lot.objects.docs.filter((v) => v.type_id === 3).length">
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.1.13.1 Дата: {{ getDateFromDocs(3, 'date') }}</td>
                </tr>
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.1.13.2 Номер: {{ getDateFromDocs(3, 'number') }}</td>
                </tr>
              </tbody>
            </template>
            <!--                  <tr>-->
            <!--                    <td colspan="1" class="empty-table-block"></td>-->
            <!--                    <td colspan="12">1.1.13.1 Дата</td>-->
            <!--                  </tr>-->
            <!--                  <tr>-->
            <!--                    <td colspan="1" class="empty-table-block"></td>-->
            <!--                    <td colspan="12">1.1.13.2 Номер</td>-->
            <!--                  </tr>-->
            <template v-if="model.getObjectLot().number_type === 'gpb_number'">
              <tbody>
                <tr>
                  <td class="text-center" colspan="13">1.2 Сведения о партии продуктов переработки зерна</td>
                </tr>
                <tr>
                  <td colspan="13">1.2.1 Номер партии: {{ model.objects.gpb.getNumber() }}</td>
                </tr>
                <tr>
                  <td colspan="13">1.2.2 Продукт переработки зерна:</td>
                </tr>
                <tr>
                  <td colspan="1" class="empty-table-block"></td>
                  <td colspan="12">1.2.2.1 Код из справочника ОКПД 2 : {{ model.objects.gpb.objects.okpd2.code }}</td>
                </tr>
                <tr>
                  <td colspan="1" class="empty-table-block"></td>
                  <td colspan="12">1.2.2.2 Код из справочника ТН ВЭД ЕАЭС: {{ tnvedById ? tnvedById.tnved : '—' }}</td>
                </tr>
                <tr>
                  <td colspan="1" class="empty-table-block"></td>
                  <td colspan="12">1.2.2.3 Наименование продукта: {{ model.objects.gpb.objects.okpd2.name }}</td>
                </tr>
                <tr>
                  <td colspan="13">1.2.3 Дата изготовления: {{ model.objects.gpb.create_date }}</td>
                </tr>
                <tr>
                  <td colspan="13">1.2.4 Производитель</td>
                </tr>
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.2.4.1 Полное наименование : {{ model.objects.gpb.objects.owner.name }}</td>
                </tr>
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.2.4.2 ИНН: {{ model.objects.gpb.objects.owner.inn }}</td>
                </tr>
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.2.4.3 КПП: {{ model.objects.gpb.objects.owner.kpp }}</td>
                </tr>
                <tr>
                  <td colspan="13">1.2.5 Цель использования: {{ model.objects.gpb.objects.target.name }}</td>
                </tr>
                <tr>
                  <td colspan="13">1.2.6 Назначение: {{ model.objects.gpb.objects.purpose.name }}</td>
                </tr>
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.2.7 Масса (нетто в килограммах): {{ model.objects.gpb.amount_kg_original }}</td>
                </tr>
                <tr>
                  <td colspan="13">
                    1.2.8 Сведения о предшествующей(-их) партии(-ях)
                    <span v-if="model.objects.gpb.objects.gpb_moved.length === 0">: —</span>
                  </td>
                </tr>
                <tr v-for="(a, i) in model.objects.gpb.objects.gpb_moved" :key="i">
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="2">1.2.8.{{ ++i }}</td>
                  <td colspan="5">{{ a.gpb_number }}</td>
                  <td colspan="5">{{ a.value }} кг</td>
                </tr>
                <tr>
                  <td colspan="13">
                    1.2.9 Потребительские свойства партии:
                    <span v-if="model.objects.gpb.objects.quality_indicators.length === 0">: —</span>
                  </td>
                </tr>
                <tr v-for="(a, i) in model.objects.gpb.objects.quality_indicators" :key="i">
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="2">1.1.9.{{ ++i }}</td>
                  <td colspan="4">{{ a.name }}</td>
                  <td colspan="3">{{ a.value }}</td>
                  <!--                <td colspan="3">{{ /\((.*)\)/.exec(a.quality_indicators)[1] }}</td>-->
                  <td colspan="3">{{ a.measure }}</td>
                </tr>
                <tr>
                  <td colspan="13">
                    1.2.10 Страна происхождения:
                    {{
                      model.objects.gpb.origin_location !== null
                        ? model.objects.gpb.origin_location['address']
                        : 'Россия'
                    }}
                  </td>
                </tr>
                <tr>
                  <td colspan="13">
                    1.2.11 Декларация о соответствии<span
                      v-if="!model.objects.gpb.objects.docs.filter((v) => v.type_id === 1).length"
                      >: —</span
                    >
                  </td>
                </tr>
              </tbody>

              <tbody v-if="model.objects.gpb.objects.docs.filter((v) => v.type_id === 1).length">
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.2.11.1 Дата: {{ getDateFromDocs(1, 'date') }}</td>
                </tr>
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.2.11.2 Номер: {{ getDateFromDocs(1, 'number') }}</td>
                </tr>
              </tbody>
              <tr>
                <td colspan="13">
                  1.2.12 Фитосанитарный сертификат<span
                    v-if="!model.objects.gpb.objects.docs.filter((v) => v.type_id === 2).length"
                    >: —</span
                  >
                </td>
              </tr>

              <tbody v-if="model.objects.gpb.objects.docs.filter((v) => v.type_id === 2).length">
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.2.12.1 Дата: {{ getDateFromDocs(2, 'date') }}</td>
                </tr>
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.2.12.2 Номер: {{ getDateFromDocs(2, 'number') }}</td>
                </tr>
              </tbody>
              <tr>
                <td colspan="13">
                  1.2.13 Ветеринарный сертификат<span
                    v-if="!model.objects.gpb.objects.docs.filter((v) => v.type_id === 3).length"
                    >: —</span
                  >
                </td>
              </tr>

              <tbody v-if="model.objects.gpb.objects.docs.filter((v) => v.type_id === 3).length">
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.2.13.1 Дата: {{ getDateFromDocs(3, 'date') }}</td>
                </tr>
                <tr>
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="12">1.2.13.2 Номер: {{ getDateFromDocs(3, 'number') }}</td>
                </tr>
              </tbody>
            </template>
            <tbody
              v-if="
                model.objects.operations.types.indexOf(' Реализация') !== -1 ||
                model.prototype_sdiz === 2 ||
                model.prototype_sdiz === 3
              "
            >
              <tr>
                <td class="text-center" colspan="13">2. Сведения о реализации</td>
              </tr>
              <tr>
                <td colspan="13">2.1 Продавец</td>
              </tr>
              <tr>
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">2.1.1 Полное наименование: {{ model.objects.seller.name }}</td>
              </tr>
              <tr>
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">2.1.2 ИНН: {{ model.objects.seller.inn }}</td>
              </tr>
              <tr>
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">2.1.3 КПП: {{ model.objects.seller.kpp }}</td>
              </tr>
              <tr v-show="model.prototype_sdiz === 2">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">
                  2.1.4 Страна-экспортер: {{ model.shipper_location !== null ? model.shipper_location.address : '-' }}
                </td>
              </tr>
              <tr>
                <td colspan="13">2.2 Покупатель</td>
              </tr>
              <tr>
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">2.2.1 Полное наименование: {{ model.objects.buyer.name }}</td>
              </tr>
              <tr>
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">2.2.2 ИНН: {{ model.objects.buyer.inn }}</td>
              </tr>
              <tr>
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">2.2.3 КПП: {{ model.objects.buyer.kpp }}</td>
              </tr>
              <tr v-show="model.prototype_sdiz === 3">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">
                  2.2.4 Страна-импортер:
                  {{ model.consignee_location !== null ? model.consignee_location.address : '-' }}
                </td>
              </tr>
              <tr>
                <td colspan="13">2.3 Сведения о гражданско-правовом договоре</td>
              </tr>
              <tr>
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">2.3.1 Дата: {{ model.contract_date }}</td>
              </tr>
              <tr>
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">2.3.2 Номер: {{ model.contract_number }}</td>
              </tr>
              <tr>
                <td colspan="13">
                  2.4 Номер закупки в Единой информационной системе в сфере закупок:<span
                    v-if="model.eisz_number === ''"
                    >: —</span
                  >
                  {{ model.eisz_number }}
                </td>
              </tr>
              <tr>
                <td colspan="13">
                  2.5 Государственный контракт с агентом<span v-if="!model.gka_date && !model.gka_number">: —</span>
                </td>
              </tr>
              <tr v-if="model.gka_date">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">2.5.1 Дата: {{ model.gka_date }}</td>
              </tr>
              <tr v-if="model.gka_number">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">2.5.2 Номер: {{ model.gka_number }}</td>
              </tr>
              <tr>
                <td colspan="13">
                  2.6 Контракт (договор, соглашение), заключенный между участниками внешнеторговой деятельности:
                </td>
              </tr>
              <tr>
                <td colspan="1" class="empty-table-block"></td>
                <td colspan="12">2.6.1 Дата: {{ model.ved_con_date }}</td>
              </tr>
              <tr>
                <td colspan="1" class="empty-table-block"></td>
                <td colspan="12">2.6.2 Номер: {{ model.ved_con_number }}</td>
              </tr>
              <tr>
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">2.6.3 Дополнительное соглашение:</td>
              </tr>
              <tr>
                <td colspan="2" class="empty-table-block"></td>
                <td colspan="11">2.6.3.1 Дата: {{ model.ved_dop_date }}</td>
              </tr>
              <tr>
                <td colspan="2" class="empty-table-block"></td>
                <td colspan="11">2.6.3.2 Номер: {{ model.ved_dop_number }}</td>
              </tr>
            </tbody>
            <tbody
              v-if="
                model.objects.operations.types.indexOf(' Приемка') !== -1 ||
                model.objects.operations.types.indexOf(' Отгрузка') !== -1 ||
                model.objects.operations.types.indexOf(' Перевозка') !== -1
              "
            >
              <tr>
                <td class="text-center" colspan="13">3. Сведения о перевозке и (или) приемке и (или) отгрузке</td>
              </tr>
              <tr v-if="showShippingOrRev()">
                <td colspan="13">3.1 Грузоотправитель</td>
              </tr>
              <tr v-if="showShippingOrRev()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">3.1.1 Полное наименование: {{ model.objects.shipper.name }}</td>
              </tr>
              <tr v-if="showShippingOrRev()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">3.1.2 ИНН: {{ model.objects.shipper.inn }}</td>
              </tr>
              <tr v-if="showShippingOrRev()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">3.1.3 КПП: {{ model.objects.shipper.kpp }}</td>
              </tr>
              <tr v-if="showShippingOrRev()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">
                  3.1.4 Пункт отправления: {{ model.shipper_location !== null ? model.shipper_location.address : '-' }}
                </td>
              </tr>
              <tr v-if="showShippingOrRev()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">
                  3.1.5 Реестровый номер организации, осуществляющей хранение:
                  {{ model.objects.shipper.registration_number }}
                </td>
              </tr>

              <tr v-if="showShippingOrApp()">
                <td colspan="13">3.2 Грузополучатель</td>
              </tr>
              <tr v-if="showShippingOrApp()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">3.2.1 Полное наименование: {{ model.objects.consignee.name }}</td>
              </tr>
              <tr v-if="showShippingOrApp()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">3.2.2 ИНН: {{ model.objects.consignee.inn }}</td>
              </tr>
              <tr v-if="showShippingOrApp()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">3.2.3 КПП: {{ model.objects.consignee.kpp }}</td>
              </tr>
              <tr v-if="showShippingOrApp()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">
                  3.2.4 Пункт назначения:
                  {{ model.consignee_location !== null ? model.consignee_location.address : '-' }}
                </td>
              </tr>
              <tr v-if="showShippingOrApp()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">
                  3.2.5 Реестровый номер организации, осуществляющей хранение:
                  {{ model.objects.consignee.registration_number }}
                </td>
              </tr>
              <tr v-if="showShipping()">
                <td colspan="13">3.3 Перевозчик</td>
              </tr>
              <tr v-if="showShipping()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">3.3.1 Полное наименование: {{ model.objects.carrier.name }}</td>
              </tr>
              <tr v-if="showShipping()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">3.3.2 ИНН: {{ model.objects.carrier.inn }}</td>
              </tr>
              <tr v-if="showShipping()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">3.3.3 КПП: {{ model.objects.carrier.kpp }}</td>
              </tr>
              <tr v-if="showShipping()">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="12">
                  3.3.4 Место(-а) перегрузки партии зерна или партии продуктов переработки зерна:
                  {{ model.carrier_location !== null ? model.carrier_location.address : '-' }}
                </td>
              </tr>
              <tr v-if="model.objects.operations.types.indexOf(' Перевозка') !== -1">
                <td colspan="13">3.4 Транспортное(-ые) средство(-а)</td>
              </tr>
              <template v-if="model.objects.operations.types.indexOf(' Перевозка') !== -1">
                <tr v-for="(v, i) in model.objects.docs_transports" :key="i">
                  <td class="empty-table-block" colspan="1"></td>
                  <td colspan="1">3.4.{{ ++i }}</td>
                  <td colspan="4">{{ v.type.label }}</td>
                  <td colspan="4">{{ v.number_tc }}</td>
                  <td colspan="3">{{ v.number }}</td>
                </tr>
              </template>

              <tr>
                <td colspan="13">
                  3.5 Реквизиты иных товаросопроводительных документов, необходимых при перевозке и (или) приемке и
                  (или) отгрузке партии зерна или партии продуктов переработки зерна
                </td>
              </tr>
              <tr v-for="(v, i) in model.objects.docs_transports_other" :key="i">
                <td class="empty-table-block" colspan="1"></td>
                <td colspan="2">3.5.{{ ++i }}</td>
                <td colspan="4">{{ v.type }}</td>
                <td colspan="3">{{ v.date }}</td>
                <td colspan="3">{{ v.number }}</td>
              </tr>
            </tbody>
          </table>
        </v-col>

        <v-col class="text-right" cols="6" style="line-height: 40px">
          <p>Достоверность представленных мною сведений</p>
        </v-col>
        <v-col cols="6" style="line-height: 40px">
          <div class="square-b"></div>
          <p>подтверждаю</p>
        </v-col>

        <v-col cols="12">
          <table style="table-layout: fixed">
            <tr>
              <td class="text-center" colspan="1">{{ model.enter_date }}</td>
              <td class="text-center" colspan="2">{{ model.authorized_person }}</td>
            </tr>
          </table>
          <br />
          <br />
          <p class="text-center">Место проставления подписи</p>
        </v-col>
      </v-row>
    </v-col>
    <v-fab-transition>
      <v-btn class="back-btn print-btn" fixed left onclick="location.reload()" style="transform: rotate(180deg)" top>
        <img class="icon" src="/icons/arrow.svg" />
      </v-btn>
    </v-fab-transition>
    <v-fab-transition>
      <v-btn bottom right icon class="gold print-btn" fab fixed onclick="window.print()">
        <icon-component height="30" width="30" icon-color="#FFFFFF" viewBox="796 675.578 200 200">
          <print-icon />
        </icon-component>
      </v-btn>
    </v-fab-transition>
  </v-row>
</template>

<script lang="ts">
import { Component, Model, Vue } from 'vue-property-decorator';
import { SdizVueModel } from '@/models/Sdiz/Data/Sdiz.vue';
import { SdizGpbVueModel } from '@/models/Sdiz/Data/SdizGpb.vue';
import IconComponent from '@/components/common/IconComponent/IconComponent.vue';
import PrintIcon from '@/components/common/IconComponent/icons/PrintIcon.vue';
import EditIcon from '@/components/common/IconComponent/icons/EditIcon.vue';
import config from '@/views/NSI/config';

@Component({
  name: 'print-form',
  components: {
    EditIcon,
    PrintIcon,
    IconComponent,
  },
})
export default class PrintForm extends Vue {
  @Model('change', { type: Object, required: true }) readonly model!: SdizGpbVueModel | SdizVueModel;
  qrLink = '';

  get tnvedList() {
    const productType = this.model.product_type;
    return this.$store.getters[config['nsi-type-product-msh'].storeGetter[productType]];
  }

  get tnvedById() {
    const id = this.model.objects.lot.tnved_id || this.model.objects.gpb.tnved_id || null;
    const result = this.tnvedList.find((e) => e.id === id);
    return result;
  }

  getDateFromDocs(type_id, fld) {
    if (typeof this.model.objects.lot !== 'undefined') {
      if (this.model.getObjectLot().objects.docs.length > 0) {
        const arr = this.model.getObjectLot().objects.docs.filter((v) => v.type_id === type_id);

        if (arr.length > 0) return arr[0][fld];
      }
    }

    return '-';
  }
  async created() {
    const data = window.location.href;
    this.qrLink = `http://chart.apis.google.com/chart?choe=UTF-8&chld=H&cht=qr&chs=100x100&chl=${data}`;
  }

  showShippingOrRev() {
    return (
      this.model.objects.operations.types.indexOf(' Перевозка') !== -1 ||
      this.model.objects.operations.types.indexOf(' Отгрузка') !== -1
    );
  }

  showShippingOrApp() {
    return (
      this.model.objects.operations.types.indexOf(' Перевозка') !== -1 ||
      this.model.objects.operations.types.indexOf(' Приемка') !== -1
    );
  }

  showShipping() {
    return false;
    // return this.model.objects.operations.types.indexOf(' Перевозка') !== -1 && this.model.carriers.length;
  }
}
</script>
<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';
@import '@/assets/styles/_container';

.b-title {
  width: 100%;
  display: block;
  color: $medium-grey-color;
}

.input-block {
  margin-top: 14px;
}

.show-print {
  width: 100%;
  position: absolute;
  top: 0;
  left: 0;
  z-index: 10000;
  background-color: $white-color;
}

.empty-table-block {
  background-color: $silver-color;
}

.table-bd td {
  border: 1px solid black;
  padding: 0 0 0 5px;
  border-radius: 0;
}

td {
  padding: 5px;
}

@media print {
  .show-print {
    width: 100%;
    margin: auto;
  }

  .print-btn {
    display: none;
  }
}

table {
  page-break-inside: auto;
}

tr {
  page-break-inside: avoid;
  page-break-after: auto;
  border: 1px solid $black-color;
}

thead {
  display: table-header-group;
}

#print-block {
}

#print-block .container {
  width: 820px;
  margin: 0 auto;
}

.square-b {
  width: 40px;
  height: 40px;
  border: 1px solid;
  float: left;
  margin-right: 10px;
}

.gold {
  background-color: $gold-light-color !important;
}

img.icon {
  max-width: 100%;
  width: 28px;
}

.white-icn {
  filter: invert(100%) sepia(6%) saturate(0%) hue-rotate(115deg) brightness(108%) contrast(108%);
}

.b-title {
  width: 100%;
  display: block;
  color: $medium-grey-color;
}

.input-block {
  margin-top: 14px;
}

.show-print {
  width: 100%;
  position: absolute;
  top: 0;
  left: 0;
  z-index: 10000;
  background-color: $white-color;
}

.empty-table-block {
  background-color: $silver-color;
}

.table-bd td {
  border: 1px solid $black-color;
  padding: 0 0 0 5px;
  border-radius: 0;
}

td {
  padding: 5px;
}

@media print {
  .show-print {
    width: 100%;
    margin: auto;
  }

  .print-btn {
    display: none;
  }
}

table {
  page-break-inside: auto;
}

tr {
  page-break-inside: avoid;
  page-break-after: auto;
}

thead {
  display: table-header-group;
}

#print-block {
}

#print-block .container {
  width: 820px;
  margin: 0 auto;
}

.square-b {
  width: 40px;
  height: 40px;
  border: 1px solid;
  float: left;
  margin-right: 10px;
}

.gold {
  background-color: $gold-light-color !important;
}

img.icon {
  max-width: 100%;
  width: 25px;
}

.white-icn {
  //filter: invert(100%) sepia(6%) saturate(0%) hue-rotate(115deg) brightness(108%) contrast(108%);
}
</style>
