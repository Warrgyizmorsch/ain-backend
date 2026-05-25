<section class="page-section numbers-section">
    <div class="container">
        <h2 class="section-title">
            Our <span>Success</span> in Numbers
        </h2>

        <div class="stats-container">
            <div class="stat-card">
                <i class="fas fa-clipboard-check"></i>
                <div class="stat-number">45,000+</div>
                <p>Assignments successfully completed with our expert guidance</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-smile"></i>
                <div class="stat-number">3000+</div>
                <p>UK Academic Writers</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-redo"></i>
                <div class="stat-number">98%</div>
                <p>Recurring client return rate</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-graduate"></i>
                <div class="stat-number">9/10</div>
                <p>Students Improve Grades after receiving our expert support</p>
            </div>
        </div>
    </div>
</section>


<style>
.numbers-section {
    background: #E5D4FF;
    max-width: 100%;
    padding: 30px 20px;
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 30px;
    max-width: 900px;
    margin: 0 auto;
}

.stat-card {
    background: white;
    color: #1f1f1f;
    padding: 25px 20px;
    border-radius: 10px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    border-bottom: 4px solid #5e2ced;
    text-align: center;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card i {
    font-size: 36px;
    color: #5e2ced;
    margin-bottom: 15px;
}

.stat-number {
    font-size: 28px;
    font-weight: bold;
    color: #5e2ced;
    margin-bottom: 10px;
}

.stat-card p {
    font-size: 12px;
    font-weight: 500;
    line-height: 1.3;
    margin-bottom: 0 !important;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .section-title {
        font-size: 20px;
    }

    .stats-container {
        gap: 15px;
    }

    .stat-card i {
        font-size: 28px;
    }

    .stat-number {
        font-size: 20px;
    }

    .stat-card {
        padding: 10px 5px;
    }
}
</style>
