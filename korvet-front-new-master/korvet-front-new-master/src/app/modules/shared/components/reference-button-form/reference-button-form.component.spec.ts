import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { ReferenceButtonFormComponent } from './reference-button-form.component';

describe('ReferenceButtonFormComponent', () => {
  let component: ReferenceButtonFormComponent;
  let fixture: ComponentFixture<ReferenceButtonFormComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ ReferenceButtonFormComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ReferenceButtonFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
