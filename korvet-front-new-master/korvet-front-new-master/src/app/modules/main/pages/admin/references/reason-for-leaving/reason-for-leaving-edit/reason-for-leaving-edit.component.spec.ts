import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReasonForLeavingEditComponent } from './reason-for-leaving-edit.component';

describe('ReasonForLeavingEditComponent', () => {
  let component: ReasonForLeavingEditComponent;
  let fixture: ComponentFixture<ReasonForLeavingEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ReasonForLeavingEditComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ReasonForLeavingEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
