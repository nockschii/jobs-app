<template>
  <div class="add-job-page">
    <div class="page-header">
      <h1>Neue Stellenausschreibung hinzufügen</h1>
      <p class="form-legend">
        <span class="required-indicator">*</span> Pflichtfelder
      </p>
    </div>
    
    <div class="job-form">
      <form @submit.prevent="handleSubmit">
        <div class="form-section">
          <h2>Stelleninformationen</h2>
          
          <div class="form-group">
            <label for="title">
              Titel <span class="required-indicator">*</span>
            </label>
            <input 
              id="title"
              v-model="form.title" 
              type="text" 
              required 
              maxlength="120"
              placeholder="z.B. PHP Entwickler"
              :class="{ 'error': errors.title }"
            />
            <small class="field-hint">{{ form.title.length }}/120 Zeichen</small>
            <div v-if="errors.title" class="error-text">{{ errors.title }}</div>
          </div>
          
          <div class="form-group">
            <label for="description">
              Stellenbeschreibung <span class="optional-text">(optional)</span>
            </label>
            <textarea 
              id="description"
              v-model="form.description" 
              rows="5"
              placeholder="Beschreiben Sie detailliert die Hauptaufgaben, erforderlichen Qualifikationen und was das Unternehmen bietet. Zum Beispiel: Technologien, Arbeitsumgebung, Benefits, Entwicklungsmöglichkeiten..."
              :class="{ 'error': errors.description }"
            ></textarea>
            <small class="field-hint">{{ (form.description || '').length }} Zeichen</small>
            <div v-if="errors.description" class="error-text">{{ errors.description }}</div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="department">
                Abteilung <span class="optional-text">(optional)</span>
              </label>
              <input 
                id="department"
                v-model="form.department" 
                type="text" 
                maxlength="50"
                placeholder="z.B. IT & Softwareentwicklung"
                :class="{ 'error': errors.department }"
              />
              <small class="field-hint">{{ (form.department || '').length }}/50 Zeichen</small>
              <div v-if="errors.department" class="error-text">{{ errors.department }}</div>
            </div>
            
            <div class="form-group">
              <label for="employment_type">
                Beschäftigungsart <span class="optional-text">(optional)</span>
              </label>
              <select id="employment_type" v-model="form.employment_type" :class="{ 'error': errors.employment_type }">
                <option value="">Beschäftigungsart auswählen</option>
                <option 
                  v-for="type in employmentTypes" 
                  :key="type.value" 
                  :value="type.value"
                >
                  {{ type.label }}
                </option>
              </select>
              <div v-if="errors.employment_type" class="error-text">{{ errors.employment_type }}</div>
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="city">
                Stadt <span class="optional-text">(optional)</span>
              </label>
              <input 
                id="city"
                v-model="form.city" 
                type="text" 
                maxlength="60"
                placeholder="z.B. Wien, München, Zürich"
                :class="{ 'error': errors.city }"
              />
              <small class="field-hint">{{ (form.city || '').length }}/60 Zeichen</small>
              <div v-if="errors.city" class="error-text">{{ errors.city }}</div>
            </div>
            
            <div class="form-group">
              <label for="country">
                Land <span class="optional-text">(optional)</span>
              </label>
              <input 
                id="country"
                v-model="form.country" 
                type="text" 
                maxlength="60"
                placeholder="z.B. Österreich, Deutschland, Schweiz"
                :class="{ 'error': errors.country }"
              />
              <small class="field-hint">{{ (form.country || '').length }}/60 Zeichen</small>
              <div v-if="errors.country" class="error-text">{{ errors.country }}</div>
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="application_email">
                Bewerbungs-E-Mail <span class="required-indicator">*</span>
              </label>
              <input 
                id="application_email"
                v-model="form.application_email" 
                type="email" 
                required
                maxlength="255"
                placeholder="z.B. bewerbung@unternehmen.at"
                :class="{ 'error': errors.application_email }"
              />
              <small class="field-hint">{{ (form.application_email || '').length }}/255 Zeichen</small>
              <div v-if="errors.application_email" class="error-text">{{ errors.application_email }}</div>
            </div>
            
            <div class="form-group">
              <label for="application_url">
                Bewerbungslink <span class="optional-text">(optional)</span>
              </label>
              <input 
                id="application_url"
                v-model="form.application_url" 
                type="url" 
                maxlength="200"
                placeholder="z.B. https://unternehmen.at/karriere/stellenausschreibung"
                :class="{ 'error': errors.application_url }"
              />
              <small class="field-hint">{{ (form.application_url || '').length }}/200 Zeichen</small>
              <div v-if="errors.application_url" class="error-text">{{ errors.application_url }}</div>
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <h2>Unternehmensinformationen</h2>
          
          <div class="form-group">
            <label for="company">
              Unternehmen <span class="required-indicator">*</span>
            </label>
            <select id="company" v-model="form.company_id" required :class="{ 'error': errors.company_id }">
              <option value="">Unternehmen auswählen</option>
              <option 
                v-for="company in companies" 
                :key="company.id" 
                :value="company.id"
              >
                {{ company.name }}
              </option>
            </select>
            <div v-if="errors.company_id" class="error-text">{{ errors.company_id }}</div>
          </div>
        </div>

        <div class="form-section">
          <h2>Status & Sichtbarkeit</h2>
          
          <div class="form-row">
            <div class="form-group checkbox-form-group">

              <div class="checkbox-group">
                <input 
                  id="is_active"
                  v-model="form.is_active"
                  type="checkbox"
                />
                <label for="is_active" class="checkbox-label">
                  Stellenausschreibung aktiv schalten
                </label>
              </div>
              <small class="field-hint">
                Aktive Stellenausschreibungen werden öffentlich angezeigt und können von Bewerbern gefunden werden.
              </small>
              <div v-if="errors.is_active" class="error-text">{{ errors.is_active }}</div>
            </div>
            
            <div class="form-group">
              <!-- Platzhalter für zusätzliche Felder -->
            </div>
          </div>
        </div>
        
        <div class="form-actions">
          <button type="button" class="btn-example" @click="loadExample">
            📝 Beispiel laden
          </button>
          <a href="/" class="btn-cancel">
            Abbrechen
          </a>
          <button type="submit" class="btn-submit" :disabled="loading">
            {{ loading ? 'Erstelle...' : 'Stellenausschreibung erstellen' }}
          </button>
        </div>
      </form>
    </div>
    
    <div v-if="error" class="error-message">
      {{ error }}
    </div>
  </div>
</template>

<script>
import { fetchCompanies } from '@api/companiesApi.js';
import { createJob } from '@api/jobsApi.js';

export default {
  name: 'AddJob',
  emits: ['cancel', 'job-created'],
  data() {
    return {
      loading: false,
      error: '',
      errors: {},
      companies: [],
      employmentTypes: [
        { value: 'Vollzeit', label: 'Vollzeit' },
        { value: 'Teilzeit', label: 'Teilzeit' },
        { value: 'Vertrag', label: 'Vertrag' },
        { value: 'Praktikum', label: 'Praktikum' },
        { value: 'Befristet', label: 'Befristet' },
        { value: 'Freiwillig', label: 'Freiwillig' },
        { value: 'Freelance', label: 'Freelance' }
      ],
      form: {
        title: '',
        description: '',
        department: '',
        city: '',
        country: '',
        application_email: '',
        application_url: '',
        employment_type: '',
        company_id: '',
        is_active: false
      }
    }
  },
  async mounted() {
    await this.loadCompanies();
  },
  methods: {
    async loadCompanies() {
      try {
        const response = await fetchCompanies();
        this.companies = response.data;
      } catch (error) {
        this.error = 'Fehler beim Laden der Unternehmen';
      }
    },
    async handleSubmit() {
      this.loading = true;
      this.error = '';
      
      try {
        const jobData = { ...this.form };
              const nullableFields = ['description', 'department', 'city', 'country', 'application_url', 'employment_type'];
        nullableFields.forEach(field => {
          if (!jobData[field] || (typeof jobData[field] === 'string' && jobData[field].trim() === '')) {
            jobData[field] = null;
          }
        });
        
        const response = await createJob(jobData);
        this.$emit('job-created', response.data);
        window.location.href = '/';
      } catch (error) {
        if (error.response?.status === 422) {
          const serverErrors = error.response.data.errors || {};
          this.errors = { ...this.errors, ...serverErrors };
          this.error = 'Bitte überprüfen Sie Ihre Eingabedaten';
        } else {
          this.error = error.response?.data?.message || 'Fehler beim Erstellen der Stellenausschreibung';
        }
      } finally {
        this.loading = false;
      }
    },
    
    loadExample() {
      this.form = {
        title: 'TestJob Bezeichnung#1 - Bezeichnung_2',
        description: 'Wir brauchen diesen Testjob zum Testen',
        department: 'Softwaretesting',
        city: 'Wien',
        country: 'Österreich',
        application_email: 'bewerbung@tech-firma.at',
        application_url: 'https://tech-firma.at/karriere/frontend-entwickler',
        employment_type: 'Vollzeit',
        company_id: this.companies.length > 0 ? this.companies[0].id : '',
        is_active: true
      };
      this.errors = {}; // Fehler zurücksetzen
    }
  }
}
</script>

<style scoped>
.add-job-page {
  max-width: 900px;
  margin: 0 auto;
  padding: 2rem;
}

.page-header {
  margin-bottom: 2rem;
}

.page-header h1 {
  color: #333;
  font-size: 2rem;
  margin: 0 0 0.5rem 0;
}

.form-legend {
  color: #6b7280;
  font-size: 0.9rem;
  margin: 0;
}

.required-indicator {
  color: #dc2626;
  font-weight: bold;
}

.optional-text {
  color: #6b7280;
  font-weight: normal;
  font-size: 0.9rem;
}

.job-form {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 2rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.form-section {
  margin-bottom: 2rem;
}

.form-section h2 {
  color: #555;
  font-size: 1.25rem;
  margin-bottom: 1.5rem;
  border-bottom: 2px solid #f3f4f6;
  padding-bottom: 0.75rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #333;
}

.field-hint {
  font-size: 0.75rem;
  color: #6b7280;
  margin-top: 0.25rem;
  white-space: nowrap;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 1rem;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #4f46e5;
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-group input.error,
.form-group select.error,
.form-group textarea.error {
  border-color: #dc2626;
  box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.error-text {
  color: #dc2626;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.form-group textarea {
  resize: vertical;
  min-height: 120px;
}

.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #e5e7eb;
}

.btn-example {
  padding: 0.75rem 1.5rem;
  background: #10b981;
  color: white;
  border: none;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
  text-decoration: none;
  display: inline-block;
  text-align: center;
}

.btn-example:hover {
  background: #059669;
}

.btn-cancel,
.btn-submit {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
  text-decoration: none;
  display: inline-block;
  text-align: center;
}

.btn-cancel {
  background: #6b7280;
  color: white;
}

.btn-cancel:hover {
  background: #4b5563;
}

.btn-submit {
  background: #4f46e5;
  color: white;
}

.btn-submit:hover:not(:disabled) {
  background: #4338ca;
}

.btn-submit:disabled {
  background: #9ca3af;
  cursor: not-allowed;
}

.error-message {
  background: #fef2f2;
  color: #dc2626;
  padding: 1rem;
  border: 1px solid #fecaca;
  border-radius: 6px;
  margin-top: 1rem;
}

.checkbox-form-group .main-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #333;
}

.checkbox-group {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  margin-top: 0.5rem;
}

.checkbox-label {
  margin-bottom: 0 !important;
  font-weight: 500;
  cursor: pointer;
  line-height: 1.4;
}

.checkbox-group input[type="checkbox"] {
  width: auto;
  margin: 0;
  margin-top: 0.1rem;
  transform: scale(1.1);
  flex-shrink: 0;
}

@media (max-width: 768px) {
  .add-job-page {
    padding: 1rem;
  }
  
  .form-row {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
  
  .form-actions {
    flex-direction: column;
  }
}
</style>