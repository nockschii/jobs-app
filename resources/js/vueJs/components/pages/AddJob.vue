<template>
  <div class="add-job-page">
    <div class="page-header">
      <h1>Neue Stellenausschreibung hinzufügen</h1>
    </div>
    
    <div class="job-form">
      <form @submit.prevent="handleSubmit">
        <div class="form-section">
          <h2>Stelleninformationen</h2>
          
          <div class="form-group">
            <label for="title">Titel *</label>
            <input 
              id="title"
              v-model="form.title" 
              type="text" 
              required 
              maxlength="120"
              placeholder="z.B. Entwickler"
            />
            <small class="field-hint">{{ form.title.length }}/120 Zeichen</small>
          </div>
          
          <div class="form-group">
            <label for="description">Stellenbeschreibung</label>
            <textarea 
              id="description"
              v-model="form.description" 
              rows="5"
              placeholder="Beschreiben Sie detailliert die Hauptaufgaben, erforderlichen Qualifikationen und was das Unternehmen bietet. Zum Beispiel: Technologien, Arbeitsumgebung, Benefits, Entwicklungsmöglichkeiten..."
            ></textarea>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="department">Abteilung *</label>
              <input 
                id="department"
                v-model="form.department" 
                type="text" 
                required 
                maxlength="50"
                placeholder="z.B. IT & Softwareentwicklung"
              />
            </div>
            
            <div class="form-group">
              <label for="employment_type">Beschäftigungsart</label>
              <select id="employment_type" v-model="form.employment_type">
                <option value="">Beschäftigungsart auswählen</option>
                <option value="full-time">Vollzeit</option>
                <option value="part-time">Teilzeit</option>
                <option value="contract">Befristet</option>
                <option value="freelance">Freiberuflich</option>
                <option value="internship">Praktikum</option>
              </select>
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="city">Stadt *</label>
              <input 
                id="city"
                v-model="form.city" 
                type="text" 
                required 
                maxlength="60"
                placeholder="z.B. Wien, München, Zürich"
              />
            </div>
            
            <div class="form-group">
              <label for="country">Land *</label>
              <input 
                id="country"
                v-model="form.country" 
                type="text" 
                required 
                maxlength="60"
                placeholder="z.B. Österreich, Deutschland, Schweiz"
              />
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="application_email">Bewerbungs-E-Mail *</label>
              <input 
                id="application_email"
                v-model="form.application_email" 
                type="email" 
                required 
                maxlength="255"
                placeholder="z.B. bewerbung@unternehmen.at"
              />
            </div>
            
            <div class="form-group">
              <label for="application_url">Bewerbungslink *</label>
              <input 
                id="application_url"
                v-model="form.application_url" 
                type="url" 
                required 
                maxlength="500"
                placeholder="z.B. https://unternehmen.at/karriere/stellenausschreibung"
              />
            </div>
          </div>
        </div>
        
        <div class="form-section">
          <h2>Unternehmensinformationen</h2>
          
          <div class="form-group">
            <label for="company">Unternehmen *</label>
            <select id="company" v-model="form.company_id" required>
              <option value="">Unternehmen auswählen</option>
              <option 
                v-for="company in companies" 
                :key="company.id" 
                :value="company.id"
              >
                {{ company.name }}
              </option>
            </select>
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
            {{ loading ? 'Erstelle...' : 'Stelle erstellen' }}
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
      companies: [],
      form: {
        title: '',
        description: '',
        department: '',
        city: '',
        country: '',
        application_email: '',
        application_url: '',
        employment_type: '',
        company_id: ''
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
        // Leere Strings in null konvertieren für nullable Felder
        if (!jobData.description) jobData.description = null;
        if (!jobData.employment_type) jobData.employment_type = null;
        
        const response = await createJob(jobData);
        // Erfolgreiche Erstellung - zur Startseite weiterleiten
        window.location.href = '/';
      } catch (error) {
        if (error.response?.status === 422) {
          this.error = 'Bitte überprüfen Sie Ihre Eingabedaten';
        } else {
          this.error = error.response?.data?.message || 'Fehler beim Erstellen der Stelle';
        }
      } finally {
        this.loading = false;
      }
    },
    
    loadExample() {
      this.form = {
        title: 'Test Job',
        description: 'Wir brauchen einen Test Job für die Anwendung."',
        department: 'IT & Softwareentwicklung',
        city: 'Wien',
        country: 'Österreich',
        application_email: 'bewerbung@tech-firma.at',
        application_url: 'https://tech-firma.at/karriere/test-job',
        employment_type: 'Vollzeit',
        company_id: this.companies.length > 0 ? this.companies[0].id : ''
      };
    }
  }
}
</script>

<style scoped>
.add-job-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
}

.page-header {
  margin-bottom: 2rem;
}

.page-header h1 {
  color: #333;
  font-size: 2rem;
  margin: 0;
}

.job-form {
  background: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 2rem;
}

.form-section {
  margin-bottom: 2rem;
}

.form-section h2 {
  color: #555;
  font-size: 1.25rem;
  margin-bottom: 1rem;
  border-bottom: 1px solid #eee;
  padding-bottom: 0.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
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
  display: block;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #4f46e5;
  box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
}

.form-group textarea {
  resize: vertical;
  min-height: 100px;
}

.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 1px solid #eee;
}

.btn-example {
  padding: 0.75rem 1.5rem;
  background: #10b981;
  color: white;
  border: none;
  border-radius: 4px;
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
  border-radius: 4px;
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
  border-radius: 4px;
  margin-top: 1rem;
}

@media (max-width: 768px) {
  .add-job-page {
    padding: 1rem;
  }
  
  .form-row {
    grid-template-columns: 1fr;
  }
  
  .form-actions {
    flex-direction: column;
  }
}
</style>
