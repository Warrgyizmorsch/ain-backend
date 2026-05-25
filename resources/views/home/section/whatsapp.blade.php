<section class="whatsapp-card">
    <div class="container">
<div class="whatsapp-banner">
    <div class="whatsapp-content">
        <img src="/images/whatsapp copy.png" alt="WhatsApp" class="whatsapp-icon">
        <div class="whatsapp-brand">
        <p class="help">Get Instant Assignment Help on </p>
        <p class="whatsapp-name">WhatsApp</p>
    </div>
    </div>
    <a href="https://wa.me/447435256433" class="whatsapp-button" target="_blank">
        Get Instant Assistance
    </a>
    <img src="/images/mockup-whatsapp.png" alt="WhatsApp on Phone" class="whatsapp-phone">
</div>
</div>
</section>
<style>
    .whatsapp-card{
        padding: 2rem 0.5rem;
    }
    .whatsapp-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgb(1,210,8);
background: linear-gradient(180deg, rgba(1,210,8,1) 0%, rgba(7,94,55,1) 100%);
    padding: 15px 25px;
    border-radius: 12px;
    color: white;
    margin: auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Left Side Content */
.whatsapp-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.whatsapp-icon {
    width: 80px;
    height: 80px;
}
.whatsapp-brand{
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.whatsapp-name{
    font-weight: 800;
    font-size: 2rem;
    margin-bottom: 0 ;
}
.help{
    font-size: 1rem;
    margin-bottom: 0 ;
}
/* Call-to-Action Button */
.whatsapp-button {
    background-color: #FFC107;
    color: black;
    font-weight: bold;
    padding: 10px 15px;
    border-radius: 6px;
    text-decoration: none;
    transition: background 0.3s ease-in-out;
}

.whatsapp-button:hover {
    background-color: #E0A800;
}

/* Right Side Phone Image */
.whatsapp-phone {
    position: relative;
    right: -25px;
    bottom: -15px;
    height: 100px;
}

/* Responsive Design */
    @media (max-width: 768px) {
    .whatsapp-banner {
        flex-direction: column;
        text-align: center;
        align-items: center;
        padding: 18px;
        gap: 1rem;
        
    }

    .whatsapp-content {
        align-items: center;
        gap: 0px;
    }

    .whatsapp-icon {
        width: 35px;
        height: 35px;
    }
    .whatsapp-brand{
        align-items: center;
    }
    .whatsapp-name {
        font-size: 1.5rem;
    }

    .help {
        font-size: 0.7rem;
        text-align: center;
    }

    .whatsapp-button {
        font-size: 0.9rem;
        padding: 8px 12px;
    }

    .whatsapp-phone {
        display: none;
    }
}
</style>