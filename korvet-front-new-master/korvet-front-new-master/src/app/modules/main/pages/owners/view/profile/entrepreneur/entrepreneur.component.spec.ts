import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {EntrepreneurComponent} from './entrepreneur.component';

describe('OwnersViewProfileEntrepreneurComponent', () => {
  let component: EntrepreneurComponent;
  let fixture: ComponentFixture<EntrepreneurComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [EntrepreneurComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EntrepreneurComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
