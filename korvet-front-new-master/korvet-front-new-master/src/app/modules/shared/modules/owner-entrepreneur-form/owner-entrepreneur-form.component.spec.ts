import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {OwnerEntrepreneurFormComponent} from './owner-entrepreneur-form.component';

describe('OwnerEntrepreneurFormComponent', () => {
  let component: OwnerEntrepreneurFormComponent;
  let fixture: ComponentFixture<OwnerEntrepreneurFormComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [OwnerEntrepreneurFormComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OwnerEntrepreneurFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
