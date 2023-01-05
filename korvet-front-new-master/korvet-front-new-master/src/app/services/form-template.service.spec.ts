import {TestBed} from '@angular/core/testing';

import {FormTemplateService} from './form-template.service';

describe('FormTemplateService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: FormTemplateService = TestBed.get(FormTemplateService);
    expect(service).toBeTruthy();
  });
});
