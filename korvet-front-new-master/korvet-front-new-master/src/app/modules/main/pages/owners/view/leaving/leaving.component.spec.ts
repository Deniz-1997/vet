import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {LeavingHistoryComponent} from './leaving-history.component';

describe('PetsViewAppointmentsComponent', () => {
  let component: LeavingHistoryComponent;
  let fixture: ComponentFixture<LeavingHistoryComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [LeavingHistoryComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LeavingHistoryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
