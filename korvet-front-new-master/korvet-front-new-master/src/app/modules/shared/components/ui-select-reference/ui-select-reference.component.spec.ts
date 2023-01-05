import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { UiSelectReferenceComponent } from './ui-select-reference.component';

describe('UiSelectReferenceComponent', () => {
  let component: UiSelectReferenceComponent;
  let fixture: ComponentFixture<UiSelectReferenceComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ UiSelectReferenceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UiSelectReferenceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
