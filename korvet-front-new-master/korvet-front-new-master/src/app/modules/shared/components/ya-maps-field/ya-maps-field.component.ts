import {AfterViewInit, Component, Input, OnChanges, OnDestroy, OnInit, SimpleChanges} from '@angular/core';
import {FormControl} from '@angular/forms';
import {AssetsService} from '../../../../services/assets.service';
import {forkJoin, from, Observable, Subject, Subscription} from 'rxjs';
import {Store} from '@ngrx/store';
import {debounceTime, filter, map, switchMap, take, takeUntil} from 'rxjs/operators';
import {SettingsService} from '../../../../services/settings.service';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';

declare var ymaps: any;
declare var $: any;

@Component({
  selector: 'app-ya-maps-field',
  templateUrl: './ya-maps-field.component.html',
  styleUrls: ['./ya-maps-field.component.css']
})
export class YaMapsFieldComponent implements OnInit, AfterViewInit, OnDestroy, OnChanges {

  latitude: number;
  longitude: number;
  @Input() coordinatesControl: FormControl;
  @Input() addressControl: FormControl;
  private mark;
  private destroy$ = new Subject();
  private addressSubscription: Subscription;
  private map: any;

  constructor(
    private assets: AssetsService,
    private store: Store<CrudState>,
    private settings: SettingsService,
  ) {
  }

  ngOnInit() {
    if (this.coordinatesControl.value) {
      const coords = this.coordinatesControl.value.split(', ');
      if (coords.length === 2) {
        this.latitude = coords[0];
        this.longitude = coords[1];
      }
    }
    this.subscribeAddress();
  }

  ngAfterViewInit() {
    forkJoin({
      ready: from(this.assets.execScript('yamaps-lib')).pipe(
        switchMap(() => new Observable(obs => {
          ymaps.ready(() => {
            obs.next();
            obs.complete();
          });
        }))
      ),
      center: this.settings.getSetting('map.center').pipe(
        take(1),
        map(setting => {
          if (setting) {
            const centerMatch = setting.value.match(/^(\d+(\.\d+)?),\s(\d+(\.\d+)??)$/);
            if (centerMatch) {
              return [centerMatch[1], centerMatch[3]];
            }
          }
          return [55.76, 37.64]; // Moscow by default
        }),
      )
    }).pipe(takeUntil(this.destroy$)).subscribe(({center}) => {
      if (this.longitude && this.latitude) {
        this.mark = this.getPoint(this.latitude, this.longitude);
        center = [parseFloat(this.latitude.toString()), parseFloat(this.longitude.toString())];
      } else if (this.addressControl && this.addressControl.value) {
        this.geoCoderAddress(this.addressControl.value)
          .pipe(
            takeUntil(this.destroy$),
            filter(geoObject => !!geoObject),
            take(1),
          )
          .subscribe(
            geoObject => this.setCenterByGeoObject(geoObject),
            console.warn,
          );
      }
      const myMap = new ymaps.Map('form-map', {
          zoom: this.mark ? 15 : 12,
          controls: [],
          center: center,
        }),
        // Создаем экземпляр класса ymaps.control.SearchControl
        mySearchControl = new ymaps.control.SearchControl({
          options: {
            noPlacemark: true
          }
        }),
        // Результаты поиска будем помещать в коллекцию.
        mySearchResults = new ymaps.GeoObjectCollection(null, {
          hintContentLayout: ymaps.templateLayoutFactory.createClass('$[properties.name]')
        });
      this.map = myMap;
      if (this.mark) {
        myMap.geoObjects.add(this.mark);
      }
      myMap.controls.add(mySearchControl);
      myMap.geoObjects.add(mySearchResults);
      myMap.events.add('dblclick', (e) => this.setMark(e));
      // При клике по найденному объекту метка становится красной.
      mySearchResults.events.add('click', function (e) {
        e.get('target').options.set('preset', 'islands#redIcon');
      });
      // Выбранный результат помещаем в коллекцию.
      mySearchControl.events.add('resultselect', function (e) {
        const index = e.get('index');
        mySearchControl.getResult(index).then(function (res) {
          mySearchResults.add(res);
        });
      }).add('submit', function () {
        mySearchResults.removeAll();
      });

      // Вывод подсказки адресса
      /*let address;
      $('.inp-address').autocomplete({
        source: function (request, response) {
          ymaps.suggest(request.term).then(function (items) {
            address = items;
            response(address);
          });
        },
        minLength: 2,
        select: function (event, ui) {
        }
      });*/

      $('.form-map').each(function () {
        const row = $(this).parents('.form-row');
        if (!row.hasClass('form-row--column')) {
          row.addClass('form-row--block');
        }
      });
    });
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['addressControl']) {
      this.subscribeAddress();
    }
  }

  ngOnDestroy(): void {
    this.destroy$.next();
  }

  toggle(event: Event) {
    event.preventDefault();
    const _this = $(event.target),
      txtShow = 'Показать карту',
      txtHide = 'Скрыть карту';
    if (_this.hasClass('active')) {
      _this.text(txtShow).removeClass('active').parent().next().removeClass('active').css('display', 'none');
    } else {
      _this.text(txtHide).addClass('active').parent().next().addClass('active').css('display', 'table');
    }
  }

  private subscribeAddress(): void {
    if (!this.addressControl && this.coordinatesControl) {
      this.addressControl = this.coordinatesControl.parent.get('full') as FormControl;
    }
    if (this.addressSubscription) {
      this.addressSubscription.unsubscribe();
    }
    if (this.addressControl) {
      this.addressSubscription = this.addressControl.valueChanges.pipe(
        takeUntil(this.destroy$),
        filter(value => !!value),
        debounceTime(2000),
        switchMap(address => this.geoCoderAddress(address)),
        filter(geoObject => !!geoObject),
      ).subscribe((geoObject: any) => {
        this.setCenterByGeoObject(geoObject);
      });
    }
  }

  private setMark(e?): void {
    if (e) {
      e.preventDefault();
    }
    const coords = e.get('coords');
    if (coords && this.map) {
      this.setCoordinates(coords[0], coords[1]);
      const mark = this.getPoint(coords[0], coords[1]);
      if (this.mark) {
        this.map.geoObjects.remove(this.mark);
      }
      this.map.geoObjects.add(mark);
      this.mark = mark;
    }
  }

  private getPoint(latitude, longitude) {
    return new ymaps.GeoObject({
      // Описание геометрии.
      geometry: {
        type: 'Point',
        coordinates: [latitude, longitude]
      },
    }, {
      // Опции.
      // Иконка метки будет растягиваться под размер ее содержимого.
      preset: 'islands#redIcon',
      // Метку можно перемещать.
      draggable: false
    });
  }

  private setCoordinates(latitude, longitude): void {
    this.latitude = latitude;
    this.longitude = longitude;
    this.coordinatesControl.setValue([latitude, longitude].join(', '));
  }

  private geoCoderAddress(address: string): Observable<any> {
    const geoCoder = new ymaps.geocode(address);
    return from(geoCoder.then(o => o.geoObjects.get(0)));
  }

  private setCenterByGeoObject(geoObject: any): void {
    try {
      const mapContainer = $('#form-map'),
        bounds = geoObject.properties.get('boundedBy'),
        // Рассчитываем видимую область для текущего положения пользователя.
        mapState = ymaps.util.bounds.getCenterAndZoom(
          bounds,
          [mapContainer.width(), mapContainer.height()]
        );
      this.map.setCenter(mapState.center, mapState.zoom || 15);
      this.setCoordinates(mapState.center[0], mapState.center[1]);
      const mark = this.getPoint(mapState.center[0], mapState.center[1]);
      if (this.mark) {
        this.map.geoObjects.remove(this.mark);
      }
      this.map.geoObjects.add(mark);
      this.mark = mark;
    } catch (e) {
    }
  }
}
