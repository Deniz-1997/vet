import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { UiMultiSelectFieldComponent } from './ui-multi-select-field.component';

describe('UiMultiSelectFieldComponent', () => {
  let component: UiMultiSelectFieldComponent;
  let fixture: ComponentFixture<UiMultiSelectFieldComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ UiMultiSelectFieldComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UiMultiSelectFieldComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
