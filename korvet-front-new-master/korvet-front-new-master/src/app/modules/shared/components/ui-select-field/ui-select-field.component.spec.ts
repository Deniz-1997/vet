import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { UiSelectFieldComponent } from './ui-select-field.component';

describe('UiSelectFieldComponent', () => {
  let component: UiSelectFieldComponent;
  let fixture: ComponentFixture<UiSelectFieldComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ UiSelectFieldComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UiSelectFieldComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
