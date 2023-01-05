import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { UiMatSelectEnumComponent } from './ui-mat-select-enum.component';

describe('UiMatSelectEnumComponent', () => {
  let component: UiMatSelectEnumComponent;
  let fixture: ComponentFixture<UiMatSelectEnumComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ UiMatSelectEnumComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UiMatSelectEnumComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
