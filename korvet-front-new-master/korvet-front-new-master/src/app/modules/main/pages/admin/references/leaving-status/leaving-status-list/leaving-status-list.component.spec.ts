import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LeavingStatusListComponent } from './leaving-status-list.component';

describe('LeavingStatusListComponent', () => {
  let component: LeavingStatusListComponent;
  let fixture: ComponentFixture<LeavingStatusListComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ LeavingStatusListComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(LeavingStatusListComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
