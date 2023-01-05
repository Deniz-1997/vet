import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { UiAutocompleteFieldComponent } from './ui-autocomplete-field.component';

describe('UiAutocompleteFieldComponent', () => {
  let component: UiAutocompleteFieldComponent;
  let fixture: ComponentFixture<UiAutocompleteFieldComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ UiAutocompleteFieldComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UiAutocompleteFieldComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
