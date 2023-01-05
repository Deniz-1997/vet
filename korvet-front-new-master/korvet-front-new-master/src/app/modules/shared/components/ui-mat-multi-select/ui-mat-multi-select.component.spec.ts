import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { UiMatMultiSelectComponent } from './ui-mat-multi-select.component';

describe('UiMatSelectEnumComponent', () => {
  let component: UiMatMultiSelectComponent;
  let fixture: ComponentFixture<UiMatMultiSelectComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ UiMatMultiSelectComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UiMatMultiSelectComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
