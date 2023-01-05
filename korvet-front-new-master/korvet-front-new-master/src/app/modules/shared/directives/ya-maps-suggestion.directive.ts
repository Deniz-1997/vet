import {AfterViewInit, Directive, ElementRef, Input, Optional} from '@angular/core';
import {NgControl} from '@angular/forms';
import {AssetsService} from '../../../services/assets.service';
import {YaMapsFieldComponent} from '../components/ya-maps-field/ya-maps-field.component';
import {SettingsService} from '../../../services/settings.service';
import {map, switchMap, take} from 'rxjs/operators';
import {from} from 'rxjs';
import {YmapsGeoproviderService} from '../../../services/ymaps-geoprovider.service';

declare var ymaps: any;
declare var $: any;

@Directive({
  selector: '[appYaMapsSuggestion]'
})
export class YaMapsSuggestionDirective implements AfterViewInit {

  @Input() yamapsField: YaMapsFieldComponent;

  constructor(
    private element: ElementRef,
    @Optional() private control: NgControl,
    private assets: AssetsService,
    private settings: SettingsService,
    private geocoderProvider: YmapsGeoproviderService,
  ) {
  }

  ngAfterViewInit(): void {
    this.assets.execScript('yamaps-lib').then(result => {
      ymaps.ready(() => {
        let address;
        $(this.element.nativeElement).autocomplete({
          source: (request, response) => {
            this.settings.getSetting('map.boundedBy').pipe(
              take(1),
              map(setting => {
                try {
                  const boundedBy = JSON.parse(setting.value);
                  if (boundedBy instanceof Array && boundedBy.length === 2) {
                    return boundedBy;
                  }
                } catch (e) {
                }
                return null;
              }),
              switchMap(boundedBy => {
                const options = {provider: this.geocoderProvider};
                // const options = {};
                if (boundedBy) {
                  options['strictBounds'] = true;
                  options['boundedBy'] = boundedBy;
                }
                return from(ymaps.suggest(request.term, options));
              }),
            ).subscribe(items => {
              address = items;
              response(address);
            });
          },
          minLength: 2,
          select: (event, ui) => {
            console.log(event, ui);
            if (this.control && this.control.control) {
              this.control.control.setValue(ui['item']['value']);
            }
            /* if (this.yamapsField) {} TODO: можно получать координаты посредством GeoCoder API и ставить метку на карте */
          }
        });
      });
    });
  }
}
