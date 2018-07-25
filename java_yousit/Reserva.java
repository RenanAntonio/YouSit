package com.br;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author 12131100068
 */
public class Reserva {
    private String token;
    private String id_reserva;
    private String nome;
    private int qtdelugares;
    private String telefone;
    private String especial;
    private int notificationValue;

    public int getNotificationValue() {
        return notificationValue;
    }

    public void setNotificationValue(int notificationValue) {
        this.notificationValue = notificationValue;
    }
    
    public Reserva(int notificationValue, String token,String id_reserva, String nome, int qtdelugares, String telefone, String especial) {
        this.notificationValue = notificationValue;
        this.id_reserva = id_reserva;
        this.nome = nome;
        this.qtdelugares = qtdelugares;
        this.telefone = telefone;
        this.especial = especial;
        this.token = token;
    }

    public Reserva() {
    }

    public String getToken() {
        return token;
    }

    public void setToken(String token) {
        this.token = token;
    }
    
    

    public String getId_reserva() {
        return id_reserva;
    }

    public void setId_reserva(String id_reserva) {
        this.id_reserva = id_reserva;
    }

    public String getNome() {
        return nome;
    }

    public void setNome(String nome) {
        this.nome = nome;
    }

    public int getQtdelugares() {
        return qtdelugares;
    }

    public void setQtdelugares(int qtdelugares) {
        this.qtdelugares = qtdelugares;
    }

    public String getTelefone() {
        return telefone;
    }

    public void setTelefone(String telefone) {
        this.telefone = telefone;
    }

    public String getEspecial() {
        return especial;
    }

    public void setEspecial(String especial) {
        this.especial = especial;
    }

    @Override
    public String toString() {
    return this.nome;
    }
    
    
    
}
