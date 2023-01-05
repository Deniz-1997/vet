import { DaDataService } from './daData.service';
import {TestBed} from '@angular/core/testing';

import {SettingsService} from './settings.service';
import {HttpClientTestingModule, HttpTestingController} from '@angular/common/http/testing';
import {Observable, of} from 'rxjs';
import {SettingModel} from '../models/setting.models';


describe('DaDataService', () => {
  let http: HttpTestingController;
  let service: DaDataService;
  let settungService: SettingsService;

  const data: SettingModel =
    {
      key: 'dadata.apiKey',
      value: 'b7093717cdafad1de1ac816c8fcca89f39150f5c',
      id: 6
    };
  beforeEach(() => {
    const fakeSettingsService = jasmine.createSpyObj(['getSetting']);
    TestBed.configureTestingModule({
      imports: [HttpClientTestingModule],
      providers: [DaDataService, {provide: SettingsService, useValue: fakeSettingsService}]
    });
    http = TestBed.inject(HttpTestingController);
    service = TestBed.inject(DaDataService);
    settungService = TestBed.inject(SettingsService);
    fakeSettingsService.getSetting.and.returnValue(of(data));

  });
  it('проверка на существование сервиса', () => {

    expect(service).toBeTruthy();
  });


});
